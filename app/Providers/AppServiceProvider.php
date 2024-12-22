<?php

declare(strict_types=1);

namespace App\Providers;

use App\ConfigHandler;
use Illuminate\Support\ServiceProvider;

// @pest-ignore
class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(ConfigHandler::class, function () {
            return new ConfigHandler(configPath: getenv('HOME') . '/.config/filament-package-skeleton/config.json');
        });
    }

    public function register(): void
    {
        //
    }
}
