<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Services\LoterieScraperService;

class ScrapeLoterieResults extends Command
{
    protected $signature = 'scrape:loterie-results {--start=} {--end=}';
    protected $description = 'Scrapea resultados de loterías y los guarda en LoterieResults';

    protected LoterieScraperService $scraper;

    public function __construct(LoterieScraperService $scraper)
    {
        parent::__construct();
        $this->scraper = $scraper;
    }

    public function handle()
    {
        $start = $this->option('start') ? Carbon::parse($this->option('start')) : Carbon::yesterday();
        $end = $this->option('end') ? Carbon::parse($this->option('end')) : Carbon::today();

        for ($date = $start; $date->lte($end); $date->addDay()) {
            $this->info("Scraping resultados para: " . $date->format('d-m-Y'));

            $result = $this->scraper->scrapeDate($date);

            if (!$result['success']) {
                $this->error($result['message']);
                continue;
            }

            foreach ($result['results'] as $r) {
                if ($r['status'] === 'success') {
                    $this->info($r['message']);
                } else {
                    $this->warn($r['message']);
                }
            }
        }

        $this->info("Scraping finalizado.");
    }
}
