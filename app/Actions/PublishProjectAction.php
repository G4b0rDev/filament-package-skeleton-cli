<?php

declare(strict_types=1);

namespace App\Actions;

use App\Exceptions\ProjectAlreadyExistsException;
use App\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
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

        Log::debug("Cloning repository to {$path}");
        Process::run("git clone --depth 1 git@github.com:G4b0rDev/filament-package-skeleton.git {$path}");
        Log::debug('Cloned filament-package-skeleton');

        // Check the contents of the cloned directory
        $contents = File::allFiles($path);
        foreach ($contents as $file) {
            Log::debug("Cloned file: {$file->getPathname()}");
        }

        Process::path($path)->run("rm -rf {$path}/.git");

        Process::path($path)->run("cd {$path} && git init && git branch -M main");
    }
}
