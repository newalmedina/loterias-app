<?php

namespace Database\Seeders;

use App\Models\Loterie;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LoteriasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $loteries = [

            'Nacional Juega Más Pega Más',
            'Nacional Gana Más',
            'Nacional Lotería Nacional',

            'La Primera Quinielón',
            'La Primera La Quiniela',
            'La Primera Loto 5 Más',

            'Loteka Lotto',
            'Loteka Mega Chance',
            'Loteka Quiniela',
            'Loteka Toca 3',

            'La Suerte Quiniela',

            'Lotedom Quiniela',
            'Lotedom Quemaito Mayor',
            'Lotedom Agarra 4',
            'Lotedom Super Palé',

            'Lotería Real Loto Real',
            'Lotería Real Lotería Real',
            'Lotería Real Loto Pool',
            'Lotería Real Tu Fecha',
            'Lotería Real Nueva Yol',
            'Lotería Real Pega 4',

            'Leidsa Loto Más Super Más',
            'Leidsa Loto Pool',
            'Leidsa Super KINO TV',
            'Leidsa Pega 3 Más',
            'Leidsa Quiniela Palé',
            'Leidsa Super Palé',

            'King Lottery Quiniela SXM',
            'King Lottery Pick 3 SXM',
            'King Lottery Pick 4 SXM',
            'King Lottery Loto Pool SXM',
            'King Lottery Philipsburg',

        ];
        foreach ($loteries as $nombre) {

            Loterie::create(

                [
                    'nombre' => $nombre,
                    'descripcion' => $nombre,

                    'lunes_hora_fin' => '21:00',
                    'martes_hora_fin' => '21:00',
                    'miercoles_hora_fin' => '21:00',
                    'jueves_hora_fin' => '21:00',
                    'viernes_hora_fin' => '21:00',
                    'sabado_hora_fin' => '21:00',
                    'domingo_hora_fin' => '21:00',

                    'lunes_disponible' => true,
                    'martes_disponible' => true,
                    'miercoles_disponible' => true,
                    'jueves_disponible' => true,
                    'viernes_disponible' => true,
                    'sabado_disponible' => true,
                    'domingo_disponible' => true,

                    'active' => true,
                ]
            );
        }
    }
}
