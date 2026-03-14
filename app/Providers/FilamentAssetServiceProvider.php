<?php

namespace App\Providers;

use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;

class FilamentAssetServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        FilamentAsset::register([
            // Js::make('custom-tailwind', asset('js/filament/tailwind/tailwind.js')),
        ]);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Filament::serving(function () {
            // Este código se ejecuta cada vez que se sirve el panel
            if (auth()->check() && auth()->user()->change_password && !request()->routeIs('change-password.*')) {
                redirect()->route('change-password.form')->send();
            }
        });
    }
}
