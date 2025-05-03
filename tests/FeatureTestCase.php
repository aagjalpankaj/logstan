<?php

declare(strict_types=1);

namespace Tests;

use Aagjalpankaj\LaravelPackageTemplate\ServiceProvider;
use Illuminate\Contracts\Config\Repository;
use Orchestra\Testbench\TestCase;

abstract class FeatureTestCase extends TestCase
{
    protected function defineEnvironment($app): void
    {
        $app['env'] = 'local';

        tap($app['config'], function (Repository $config) {});
    }

    protected function defineRoutes($router): void
    {
        $router->get('/', function () {
            return response()->json(['message' => 'Welcome']);
        })->name('home');
    }

    protected function getPackageProviders($app): array
    {
        return [
            ServiceProvider::class,
        ];
    }
}
