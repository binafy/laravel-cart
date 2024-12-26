<?php

use Binafy\LaravelCart\LaravelCart;

test('can get correct driver of laravel cart manager', function () {
    expect(LaravelCart::getDefaultDriver())->toBe(config('laravel-cart.driver.default'));

    config(['laravel-cart.driver.default' => 'session']);
    expect(LaravelCart::getDefaultDriver())->toBe('session');
});
