<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Loterie;

class ActivateLoteryseeder extends Seeder
{
    public function run(): void
    {
        $loteries = Loterie::with('results')->get();

        foreach ($loteries as $loterie) {

            $activar = true; // asumimos activa

            foreach ($loterie->results as $result) {

                $numbers = json_decode($result->numbers, true);

                if (is_array($numbers) && count($numbers) > 3) {
                    $activar = false;
                    break; // ya no hace falta seguir revisando
                }
            }

            $loterie->active = $activar ? 1 : 0;
            $loterie->save();
        }
    }
}
