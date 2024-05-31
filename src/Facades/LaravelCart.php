<?php

namespace Binafy\LaravelCart\Facades;

use Illuminate\Support\Facades\Facade;

class LaravelCart extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return \Binafy\LaravelCart\LaravelCart::class;
    }
}
