<?php

namespace Binafy\LaravelCart\Providers;

use Binafy\LaravelCart\Manager\LaravelCartManager;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class LaravelCartServiceProvider extends ServiceProvider
{
    /**
     * Register files.
     */
    public function register(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        $this->mergeConfigFrom(__DIR__.'/../../config/laravel-cart.php', 'laravel-cart');

        $this->app->bind('laravel-cart', function (Application $app) {
            return new LaravelCartManager($app);
        });
    }

    /**
     * Boot application.
     */
    public function boot(): void
    {
        // Publish Config
        $this->publishes([
            __DIR__.'/../../config/laravel-cart.php' => config_path('laravel-cart.php'),
        ], 'laravel-cart-config');

        // Publish Migrations
        $this->publishes([
            __DIR__.'/../../database/migrations' => database_path('migrations'),
        ], 'laravel-cart-migrations');
    }
}
