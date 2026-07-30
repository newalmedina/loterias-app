<?php

namespace App\Services;

use App\Models\Loterie;
use App\Models\LoterieResults;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use DragonCode\Support\Facades\Helpers\Str;
use Carbon\Carbon;
use Symfony\Component\DomCrawler\Crawler;

class PremiosDoScraperService
{
    protected string $baseUrl = 'https://premios.do/resultados-loterias-';

    public $slugList = [];

    public function scrapeDate(Carbon $date)
    {
        $url = $this->baseUrl . $date->format('Y-m-d');

        $response = Http::get($url);

        if (!$response->ok()) {
            return [
                'success' => false,
                'message' => "No se pudo obtener la página: {$url}"
            ];
        }

        $crawler = new Crawler($response->body());

        $resultsSaved = [];

        $crawler->filter('div.card.result-card')->each(function (Crawler $card) use ($date, &$resultsSaved) {


            $lotteryNameLink = $card->filter('h5.card-title.lottery-name a');

            if (!$lotteryNameLink->count()) {
                return;
            }

            $loterieName = trim($lotteryNameLink->text());
            $loterieSlug = Str::slug($loterieName);

            $allowed = [
                'anguilla-8am',
                'anguilla-9am',
                'anguilla-10am',
                'anguilla-11am',
                'anguilla-12pm',
                'anguilla-1pm',
                'anguilla-2pm',
                'anguilla-3pm',
                'anguilla-4pm',
                'anguilla-5pm',
                'anguilla-6pm',
                'anguilla-7pm',
                'anguilla-8pm',
                'anguilla-9pm',
                'anguilla-10pm',
                'nacional-noche',
                'gana-mas',
                'pega-3-mas',
                'leidsa',
                // 'haiti-bolet-930-am',
                // 'haiti-bolet-1030-am',
                // 'haiti-bolet-1130-am',
                // 'haiti-bolet-530-pm',
                // 'haiti-bolet-630-pm',
                // 'haiti-bolet-730-pm',
                'la-primera',
                'la-primera-noche',
                'la-suerte',
                'la-suerte-6pm',
                'lotedom',
                'loteka',
                'real',
                'king-lottery-dia',
                'king-lottery-noche',
                'georgia-dia',
                'georgia-tarde',
                'georgia-noche',
                'new-jersey-tarde',
                'new-jersey-noche',
                'new-york-tarde',
                'new-york-noche',
                'florida-tarde',
                'florida-noche',
            ];

            if (!in_array($loterieSlug, $this->slugList)) {
                $this->slugList[] = $loterieSlug;
            }

            if (!in_array($loterieSlug, $allowed)) {
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | Descargar imagen
            |--------------------------------------------------------------------------
            */

            $imagePath = null;

            try {

                $imgNode = $card->filter('img.lottery-icon');

                if ($imgNode->count()) {

                    $imageUrl = trim($imgNode->attr('src'));

                    if (!str_starts_with($imageUrl, 'http')) {
                        $imageUrl = 'https://premios.do' . $imageUrl;
                    }

                    $extension = pathinfo(
                        parse_url($imageUrl, PHP_URL_PATH),
                        PATHINFO_EXTENSION
                    );

                    $extension = $extension ?: 'svg';

                    $filename = $loterieSlug . '.' . strtolower($extension);

                    $storagePath = 'loterias/' . $filename;

                    if (!Storage::disk('public')->exists($storagePath)) {

                        $imageResponse = Http::timeout(20)->get($imageUrl);

                        if ($imageResponse->successful()) {

                            Storage::disk('public')->put(
                                $storagePath,
                                $imageResponse->body()
                            );
                        }
                    }

                    if (Storage::disk('public')->exists($storagePath)) {
                        $imagePath = $storagePath;
                    }
                }
            } catch (\Throwable $e) {
                report($e);
            }

            /*
            |--------------------------------------------------------------------------
            | Crear o actualizar lotería
            |--------------------------------------------------------------------------
            */


            $loterie = Loterie::firstOrCreate(
                [
                    'slug' => $loterieSlug
                ],
                [
                    'nombre' => $loterieName,
                    'code' => $loterieSlug,
                    'active' => 1,
                    // 'image' => $imagePath,
                    'time_zone' => 'America/Santo_Domingo',
                ]
            );

            if ($imagePath && empty($loterie->image)) {
                $loterie->image = $imagePath;
                $loterie->save();
            }


            $closingNode = $card->filter('span.lottery-closing-time');

            if ($closingNode->count()) {

                $closingTime = trim($closingNode->text());

                // Convierte "9:00AM" -> "09:00:00"
                $closingTime = Carbon::createFromFormat('g:iA', strtoupper($closingTime))
                    ->format('H:i:s');

                $dayColumn = [
                    1 => 'lunes_hora_fin',
                    2 => 'martes_hora_fin',
                    3 => 'miercoles_hora_fin',
                    4 => 'jueves_hora_fin',
                    5 => 'viernes_hora_fin',
                    6 => 'sabado_hora_fin',
                    0 => 'domingo_hora_fin',
                ];

                $column = $dayColumn[$date->dayOfWeek];

                // Actualizar solamente esa columna
                $loterie->update([
                    $column => $closingTime,
                ]);
            }
            /*
            |--------------------------------------------------------------------------
            | Resultados
            |--------------------------------------------------------------------------
            */

            $numbers = $card
                ->filter('div.numbers div.result-number')
                ->each(fn(Crawler $n) => intval(trim($n->text())));

            if (empty($numbers)) {

                $resultsSaved[] = [
                    'status' => 'warning',
                    'message' => "No se encontraron números para {$loterie->nombre} en {$date->format('d-m-Y')}"
                ];

                return;
            }

            $numbersString = '[' . implode(',', $numbers) . ']';

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
