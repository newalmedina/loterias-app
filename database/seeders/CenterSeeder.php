<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Center;
use App\Models\User;

class CenterSeeder extends Seeder
{
    public function run(): void
    {
        $center = Center::firstOrCreate(
            ['name' => 'Centro general'],
            [
                'email' => 'centro@correo.com',
                'phone' => '000000000',
                'address' => 'Dirección general',
                'postal_code' => '00000',
            ]
        );

        $user = User::find(1);

        if ($user) {
            $user->center_id = $center->id;
            $user->save();
        }
    }
}
