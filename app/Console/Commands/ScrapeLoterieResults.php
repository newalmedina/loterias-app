<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Loterie;
use App\Models\LoterieResults;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use DragonCode\Support\Facades\Helpers\Str;
use Symfony\Component\DomCrawler\Crawler;

class ScrapeLoterieResults extends Command
{
    protected $signature = 'scrape:loterie-results {--start=} {--end=}';
    protected $description = 'Scrapea resultados de loterías y los guarda en LoterieResults';
    // php artisan scrape:loterie-results --start=2026-03-15 --end=2026-03-16
    // php artisan scrape:loterie-results
    public function handle()
    {
        // $start = $this->option('start') ? Carbon::parse($this->option('start')) : Carbon::today();
        // $end = $this->option('end') ? Carbon::parse($this->option('end')) : $start;

        $start = $this->option('start')
            ? Carbon::parse($this->option('start'))
            : Carbon::yesterday();

        $end = $this->option('end')
            ? Carbon::parse($this->option('end'))
            : Carbon::today();

        for ($date = $start; $date->lte($end); $date->addDay()) {
            $this->info("Scraping resultados para: " . $date->format('d-m-Y'));

            $url = "https://loteriasdominicanas.com/?date=" . $date->format('d-m-Y');

            $response = Http::get($url);
            if (!$response->ok()) {
                $this->error("No se pudo obtener la página: $url");
                continue;
            }

            $html = $response->body();
            $crawler = new Crawler($html);

            // Recorremos cada bloque de juego
            $crawler->filter('div.game-info')->each(function (Crawler $gameDiv) use ($date) {

                // Obtener el slug de la lotería
                $loterieSlug =  Str::slug(trim($gameDiv->filter('a.game-title span')->text()));

                $loterie = Loterie::where('slug', $loterieSlug)->first();
                if (!$loterie) {
                    $this->warn("No se encontró la lotería: $loterieSlug");
                    return;
                }

                // Obtener el contenedor padre que tiene los resultados y fecha
                $parent = $gameDiv->ancestors()->reduce(function ($carry, $node) {
                    return $node;
                });

                // Extraer fecha
                $fechaDiv = $gameDiv->ancestors()->first()->filter('div.session-date');
                $fechaText = $fechaDiv->count() ? trim($fechaDiv->text()) : null;

                $resultDate = $fechaText
                    ? Carbon::parse($fechaText . '-' . $date->format('Y'), 'America/Santo_Domingo')
                    : $date;

                // Extraer números
                $scoresDiv = $gameDiv->siblings()->filter('div.game-scores span.score');
                $numbers = $scoresDiv->each(fn(Crawler $span) => intval(trim($span->text())));

                if (empty($numbers)) {
                    $this->warn("No se encontraron números para {$loterie->nombre} en {$resultDate->format('d-m-Y')}");
                    return;
                }
                $numbersString = '[' . implode(',', $numbers) . ']';
                // Guardar resultado

                LoterieResults::updateOrCreate(
                    [
                        'loterie_id' => $loterie->id,
                        'date' => $resultDate->format('Y-m-d'),
                    ],
                    [
                        'numbers' =>    $numbersString  // Laravel convierte automáticamente a JSON si usas cast
                    ]
                );

                $this->info("Guardado: {$loterie->nombre} - " . implode(',', $numbers));
            });
        }

        $this->info("Scraping finalizado.");
    }
}
