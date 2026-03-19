<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Loterie;

class GetLoteries extends Command
{
    protected $signature = 'get:Loteries';
    protected $description = 'Scrape lottery games from loteriasdominicanas.com and store them';

    public function handle()
    {
        $url = "https://loteriasdominicanas.com/?date=13-03-2026";

        $response = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0'
        ])->get($url);

        if (!$response->successful()) {
            $this->error("Error fetching page");
            return;
        }

        $crawler = new Crawler($response->body());

        $crawler->filter('.game-block')->each(function ($node) {

            // Nombre del juego
            $nombre = $node->filter('.game-title span')->count()
                ? trim($node->filter('.game-title span')->text())
                : null;

            if (!$nombre) {
                return; // Saltar si no hay nombre
            }

            // Generar slug
            $slug = Str::slug($nombre);

            // Verificar si ya existe
            if (Loterie::where('slug', $slug)->exists()) {
                $this->line("Ya existe: $nombre");
                return;
            }

            // Obtener URL de la imagen
            $imageUrl = null;

            if ($node->filter('.game-logo img')->count()) {
                $imgNode = $node->filter('.game-logo img')->first();

                $imageUrl = $imgNode->attr('data-src') ?: $imgNode->attr('src');
            }
            $imagePath = null;

            if ($imageUrl) {
                try {
                    $imageContent = Http::get($imageUrl)->body();
                    $filename = $slug . '.png';
                    $path = "loterias/$filename";
                    Storage::disk('public')->put($path, $imageContent);
                    $imagePath = $path;
                } catch (\Exception $e) {
                    $this->error("Error descargando imagen de $nombre: " . $e->getMessage());
                }
            }

            // Guardar en la base de datos
            Loterie::create([
                'nombre' => $nombre,
                'slug' => $slug,
                'image' => $imagePath,
                'active' => false
            ]);

            $this->info("Insertado: $nombre");
            // dd($nombre);
        });

        $this->info("Scraping completado ✅");
    }
}
