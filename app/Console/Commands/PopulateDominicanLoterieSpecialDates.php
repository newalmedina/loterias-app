<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Loterie;
use App\Models\LoterieSpecialDate;
use Carbon\Carbon;

class PopulateDominicanLoterieSpecialDates extends Command
{

    // php artisan loteries:populate-dominican-special
    // php artisan loteries:populate-dominican-special --start=2026-12-24 --end=2027-01-01

    protected $signature = 'loteries:populate-dominican-special
                            {--start= : Fecha de inicio YYYY-MM-DD}
                            {--end= : Fecha final YYYY-MM-DD}';

    protected $description = 'Popula la tabla loterie_special_dates para las loterías dominicanas incluyendo días no disponibles y horarios especiales dinámicos.';

    // Horarios especiales usando slugs
    protected $specialDates = [
        '12-24' => [
            'quiniela-real' => ['12:55:00'],
            'quiniela-loteka' => ['17:00:00'],
            'quiniela-lotedom' => ['14:55:00'],
            'la-primera-dia' => ['12:00:00', '20:00:00'],
            'la-suerte-1230' => ['12:30:00'],
            'new-york-tarde' => ['14:30:00'],
            'new-york-noche' => ['22:30:00'],
            'anguila-manana' => ['10:00:00'],
            'anguila-medio-dia' => ['13:00:00'],
            'anguila-tarde' => ['18:00:00'],
            'king-lottery-1230' => ['12:30:00'],
            'king-lottery-730' => ['19:30:00'],
            'florida-dia' => ['13:30:00'],
            'florida-noche' => ['21:45:00'],
            'loteria-nacional' => 'not_enable',
            'gana-mas' => 'not_enable',
            'quiniela-leidsa' => 'not_enable',
        ],
        '12-25' => [
            'quiniela-real' => ['12:55:00'],
            'quiniela-lotedom' => ['14:55:00'],
            'la-primera-dia' => ['12:00:00'],
            'la-suerte-1230' => ['12:30:00'],
            'new-york-tarde' => ['14:30:00'],
            'new-york-noche' => ['22:30:00'],
            'florida-dia' => ['13:30:00'],
            'florida-noche' => ['21:45:00'],
            'loteria-nacional' => 'not_enable',
            'gana-mas' => 'not_enable',
            'quiniela-leidsa' => 'not_enable',
        ],
        '12-31' => [
            'quiniela-real' => ['12:55:00'],
            'quiniela-loteka' => ['17:00:00'],
            'quiniela-lotedom' => ['14:55:00'],
            'la-primera-dia' => ['12:00:00', '20:00:00'],
            'la-suerte-1230' => ['12:30:00'],
            'new-york-tarde' => ['14:30:00'],
            'new-york-noche' => ['22:30:00'],
            'anguila-manana' => ['10:00:00'],
            'anguila-medio-dia' => ['13:00:00'],
            'anguila-tarde' => ['18:00:00'],
            'king-lottery-1230' => ['12:30:00'],
            'king-lottery-730' => ['19:30:00'],
            'florida-dia' => ['13:30:00'],
            'florida-noche' => ['21:45:00'],
            'loteria-nacional' => 'not_enable',
            'gana-mas' => 'not_enable',
            'quiniela-leidsa' => 'not_enable',
        ],
        '01-01' => [
            'quiniela-real' => ['12:55:00'],
            'quiniela-lotedom' => ['14:55:00'],
            'la-primera-dia' => ['12:00:00'],
            'la-suerte-1230' => ['12:30:00'],
            'new-york-tarde' => ['14:30:00'],
            'new-york-noche' => ['22:30:00'],
            'florida-dia' => ['13:30:00'],
            'florida-noche' => ['21:45:00'],
            'loteria-nacional' => 'not_enable',
            'gana-mas' => 'not_enable',
            'quiniela-leidsa' => 'not_enable',
        ],
    ];

    public function handle()
    {
        $this->info("Iniciando inserción/actualización de fechas especiales...");

        // Fechas de inicio y fin
        $start = $this->option('start') ? Carbon::parse($this->option('start')) : Carbon::now()->startOfYear();
        $end = $this->option('end') ? Carbon::parse($this->option('end')) : Carbon::now()->addYear()->endOfYear();

        // Iterar día por día
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {

            $key = $date->format('m-d'); // mm-dd para comparar con specialDates

            if (!isset($this->specialDates[$key])) {
                continue; // No hay reglas especiales para este día
            }

            $lotteries = $this->specialDates[$key];

            foreach ($lotteries as $slug => $horas) {
                $loterie = Loterie::where('slug', $slug)->first();

                if (!$loterie) {
                    $this->warn("Lotería $slug no encontrada, se omite.");
                    continue;
                }

                if ($horas === 'not_enable') {
                    LoterieSpecialDate::updateOrCreate(
                        [
                            'loterie_id' => $loterie->id,
                            'date' => $date->toDateString(),
                            'end_time' => null
                        ],
                        ['not_enable' => true]
                    );
                    $this->info("{$slug} marcado como NO disponible el {$date->toDateString()}");
                } else {
                    foreach ($horas as $hora) {
                        LoterieSpecialDate::updateOrCreate(
                            [
                                'loterie_id' => $loterie->id,
                                'date' => $date->toDateString(),
                                'end_time' => $hora
                            ],
                            ['not_enable' => false]
                        );
                        $this->info("{$slug} insertado/actualizado con hora $hora el {$date->toDateString()}");
                    }
                }
            }
        }

        $this->info("¡Fechas especiales insertadas/actualizadas correctamente!");
    }
}
