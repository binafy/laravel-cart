<?php

namespace Binafy\LaravelCart\Providers;

use Binafy\LaravelCart\LaravelCart;
use Closure;
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
        $this->app->bind('laravel-cart', fn ($app) => new LaravelCart);
    }
}
