<?php

declare(strict_types=1);

namespace App\Actions;

use App\DataTransferObjects\Package;
use Illuminate\Process\Pool;
use Illuminate\Support\Facades\Process;

class HandleDependencyInstall
{
    public function __invoke(Package $package): void
    {
        $basePath = getcwd() . '/' . $package->name;

        Process::concurrently(function (Pool $pool) use ($basePath) {
            $pool
                ->path($basePath)
                ->command('composer install');

            $pool
                ->path($basePath)
                ->command('npm install');
        });
    }
}
