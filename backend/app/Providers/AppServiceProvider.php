<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configurar longitud por defecto para strings en migraciones
        // Esto evita problemas con versiones antiguas de MySQL/MariaDB
        Schema::defaultStringLength(191);
    }
}

