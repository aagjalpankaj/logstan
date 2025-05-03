<?php

declare(strict_types=1);

namespace Aagjalpankaj\LaravelPackageTemplate;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

final class ServiceProvider extends BaseServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/laravel-package-template.php' => config_path('laravel-package-template.php'),
        ], 'config');

        $this->registerCommands();
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/laravel-package-template.php', 'laravel-package-template'
        );
    }

    private function registerCommands(): void {}
}
