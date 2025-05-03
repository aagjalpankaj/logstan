<?php

declare(strict_types=1);

namespace Aagjalpankaj\Logstan;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

final class ServiceProvider extends BaseServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/logstan.php' => config_path('logstan.php'),
        ], 'config');

        $this->registerCommands();
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/logstan.php', 'logstan'
        );
    }

    private function registerCommands(): void {}
}
