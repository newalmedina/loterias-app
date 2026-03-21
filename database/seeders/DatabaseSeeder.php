<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Brand;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Administrador',
            'email' => 'ing.newal.medina@gmail.com',
            'super_admin' => 1,
            'can_admin_panel' => 1,
            'change_password' => 0,
            'password' => Hash::make("Secret15"),
        ]);

        // $this->call(UnitOfMeasureSeeder::class);
        // $this->call(SettingDataSeeder::class);
        // $this->call(CategoryDataSeeder::class);
        // $this->call(CmsContentSeeder::class);
        /* $this->call(InsertDataSeeder::class);
        $this->call(UnitOfMeasureSeeder::class);
        $this->call(BrandDataSeeder::class);
        $this->call(SupplierDataSeeder::class);
        $this->call(SettingDataSeeder::class);
        $this->call(CustomerSeeder::class);
        $this->call(OtherExpenseItemSeeder::class);
        $this->call(InsertItemsSeeder::class);
        $this->call(OrderSeeder::class);
        $this->call(OtherExpenseSeeder::class);*/
        $this->call(CenterSeeder::class);
        \Artisan::call('get:Loteries');
        // Obtener primer día del año actual
        $startDate = Carbon::now()->startOfYear()->toDateString(); // ej: 2026-01-01

        // Obtener último día del año siguiente
        $endDate = Carbon::now()->addYear()->endOfYear()->toDateString(); // ej: 2027-12-31
        // \Artisan::call('loteries:populate-dominican-special');


        // Llamar al comando con los parámetros
        \Artisan::call('loteries:populate-dominican-special', [
            '--start' => $startDate,
            '--end'   => $endDate,
        ]);

        $startDate = Carbon::now()->startOfMonth()->toDateString(); // ej: 2026-01-01
        \Artisan::call('scrape:loterie-results', [
            '--start' => $startDate,
            '--end'   => $endDate,
        ]);

        // $this->call(LoteriasSeeder::class);
        $this->call(WorldTableSeeder::class);
    }
}
