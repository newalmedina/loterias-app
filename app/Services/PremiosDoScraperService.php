<?php

namespace App\Services;

use App\Models\Loterie;
use App\Models\LoterieResults;
use Illuminate\Support\Facades\Http;
use DragonCode\Support\Facades\Helpers\Str;
use Carbon\Carbon;
use Symfony\Component\DomCrawler\Crawler;

class PremiosDoScraperService
{
    protected string $baseUrl = 'https://premios.do/resultados-loterias-';

    public function scrapeDate(Carbon $date)
    {
        $url = $this->baseUrl . $date->format('Y-m-d');
        $response = Http::get($url);

        if (!$response->ok()) {
            return [
                'success' => false,
                'message' => "No se pudo obtener la página: $url"
            ];
        }

        $html = $response->body();
        $crawler = new Crawler($html);

        $resultsSaved = [];

        // Cada "card" de lotería
        $crawler->filter('div.card.result-card')->each(function (Crawler $card) use ($date, &$resultsSaved) {

            // Nombre de la lotería
            $lotteryNameLink = $card->filter('h5.card-title.lottery-name a');
            if (!$lotteryNameLink->count()) {
                return;
            }

            $loterieName = trim($lotteryNameLink->text());
            $loterieSlug = Str::slug($loterieName);

            // ✅ Solo Anguilla y dentro de los horarios permitidos
            $allowed = [
                'anguilla-8am',
                'anguilla-9am',
                // 'anguilla-10am',
                'anguilla-11am',
                'anguilla-12pm',
                // 'anguilla-1pm',
                'anguilla-2pm',
                'anguilla-3pm',
                'anguilla-4pm',
                'anguilla-5pm',
                // 'anguilla-6pm',
                'anguilla-7pm',
                'anguilla-8pm',
                // 'anguilla-9pm',
            ];

            if (!in_array($loterieSlug, $allowed)) {
                return; // "continue"
            }

            // 🔹 Buscar o crear la lotería
            $loterie = Loterie::firstOrCreate(
                ['slug' => $loterieSlug],
                [
                    'nombre' => $loterieName,
                    'code' => $loterieSlug,
                    'active' => 1,
                ]
            );

            if (!$loterie) {
                $resultsSaved[] = [
                    'status' => 'warning',
                    'message' => "No se encontró la lotería: $loterieSlug"
                ];
                return;
            }

            // Números de resultados
            $numbers = $card->filter('div.numbers div.result-number')->each(fn(Crawler $n) => intval(trim($n->text())));

            if (empty($numbers)) {
                $resultsSaved[] = [
                    'status' => 'warning',
                    'message' => "No se encontraron números para {$loterie->nombre} en {$date->format('d-m-Y')}"
                ];
                return;
            }

            $numbersString = '[' . implode(',', $numbers) . ']';

            // Guardar o actualizar en LoterieResults
            LoterieResults::updateOrCreate(
                [
                    'loterie_id' => $loterie->id,
                    'date' => $date->format('Y-m-d'),
                ],
                [
                    'numbers' => $numbersString
                ]
            );

            $resultsSaved[] = [
                'status' => 'success',
                'message' => "Guardado: {$loterie->nombre} - " . implode(',', $numbers)
            ];
        });

        return [
            'success' => true,
            'results' => $resultsSaved
        ];
    }
}
