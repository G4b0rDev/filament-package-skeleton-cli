<?php

declare(strict_types=1);

namespace App\Actions;

use App\Exceptions\ProjectAlreadyExistsException;
use App\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;
use RuntimeException;

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

        $token = env('ACCESS_TOKEN');
        $cloneCommand = $token
            ? "git clone --depth 1 https://{$token}:x-oauth-basic@github.com/G4b0rDev/filament-package-skeleton.git {$path}"
            : "git clone --depth 1 git@github.com:G4b0rDev/filament-package-skeleton.git {$path}";

        $process = Process::run($cloneCommand);

        if ($process->exitCode() !== 0) {
            throw new RuntimeException('Failed to clone repository: '.$process->errorOutput());
        }

        Log::debug('Cloned filament-package-skeleton');

        Process::path($path)->run("rm -rf {$path}/.git");
        Process::path($path)->run("cd {$path} && git init && git branch -M main");
    }
}
