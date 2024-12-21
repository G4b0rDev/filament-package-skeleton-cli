<?php

declare(strict_types=1);

namespace App\Actions;

use App\Exceptions\ProjectAlreadyExistsException;
use App\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

class PublishProjectAction
{
    public function __invoke(string $projectName): void
    {
        $basePath = Config::basePath();
        $path = "{$basePath}/{$projectName}";

        if (File::exists($path)) {
            throw ProjectAlreadyExistsException::create($path);
        }

        File::ensureDirectoryExists($path);

        Process::run("git clone --depth 1 git@github.com:G4b0rDev/filament-package-skeleton.git {$path}");

        Process::path($path)->run("rm -rf {$path}/.git");

        Process::path($path)->run("cd {$path} && git init && git branch -M main");
    }
}
