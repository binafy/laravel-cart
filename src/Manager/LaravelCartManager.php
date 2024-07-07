<?php

namespace Binafy\LaravelCart\Manager;

use Illuminate\Support\Manager;

class LaravelCartManager extends Manager
{
    /**
     * Get the default driver.
     */
    public function getDefaultDriver(): string
    {
        return 'database';
    }

    /**
     * The database driver of laravel cart.
     */
    public function createDatabaseDriver(): LaravelCartDatabase
    {
        return new LaravelCartDatabase();
    }
}
