<?php

namespace App\Services;

use App\Models\Loterie;
use App\Models\LoterieResults;
use Illuminate\Support\Facades\Http;
use DragonCode\Support\Facades\Helpers\Str;
use Carbon\Carbon;
use Symfony\Component\DomCrawler\Crawler;

class LoterieScraperService
{
    public function scrapeDate(Carbon $date)
    {
        $url = "https://loteriasdominicanas.com/?date=" . $date->format('d-m-Y');
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

        $crawler->filter('div.game-info')->each(function (Crawler $gameDiv) use ($date, &$resultsSaved) {

            $loterieSlug = Str::slug(trim($gameDiv->filter('a.game-title span')->text()));
            $loterie = Loterie::where('slug', $loterieSlug)->first();

            if (!$loterie) {
                $resultsSaved[] = [
                    'status' => 'warning',
                    'message' => "No se encontró la lotería: $loterieSlug"
                ];
                return;
            }

            $fechaDiv = $gameDiv->ancestors()->first()->filter('div.session-date');
            $fechaText = $fechaDiv->count() ? trim($fechaDiv->text()) : null;

            $resultDate = $fechaText
                ? Carbon::parse($fechaText . '-' . $date->format('Y'), 'America/Santo_Domingo')
                : $date;

            $scoresDiv = $gameDiv->siblings()->filter('div.game-scores span.score');
            $numbers = $scoresDiv->each(fn(Crawler $span) => intval(trim($span->text())));

            if (empty($numbers)) {
                $resultsSaved[] = [
                    'status' => 'warning',
                    'message' => "No se encontraron números para {$loterie->nombre} en {$resultDate->format('d-m-Y')}"
                ];
                return;
            }

            $numbersString = '[' . implode(',', $numbers) . ']';

            LoterieResults::updateOrCreate(
                [
                    'loterie_id' => $loterie->id,
                    'date' => $resultDate->format('Y-m-d'),
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
