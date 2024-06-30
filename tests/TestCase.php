<?php

namespace Tests;

use Binafy\LaravelCart\Providers\LaravelCartServiceProvider;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Artisan;
use Tests\SetUp\Models\User;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Load package service provider.
     *
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function getPackageProviders($app): array
    {
        return [LaravelCartServiceProvider::class];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function getEnvironmentSetUp($app): void
    {
        // Set default database to use sqlite :memory:
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        // Set app key
        $app['config']->set('app.key', 'base64:'.base64_encode(
            Encrypter::generateKey(config()['app.cipher'])
        ));

        // Set user model
        $app['config']->set('auth.providers.users.model', User::class);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__.'/SetUp/Migrations');

        Artisan::call('migrate');
    }
}
