<?php

namespace Binafy\LaravelCart\Providers;

use Illuminate\Support\ServiceProvider;

class LaravelCartServiceProvider extends ServiceProvider
{
    /**
     * Register files.
     */
    public function register(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->mergeConfigFrom(__DIR__ . '/../../config/laravel-cart.php', 'laravel-cart');
    }

    /**
     * Boot application.
     */
    public function boot(): void
    {
        // Publish Config file
        $this->publishes([
            __DIR__ . '/../../config/laravel-cart.php' => config_path('laravel-cart.php'),
        ], 'laravel-cart-config');
    }
}
