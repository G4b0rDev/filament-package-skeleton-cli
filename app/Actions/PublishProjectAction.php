<?php

declare(strict_types=1);

namespace App\Actions;

use App\Exceptions\ProjectAlreadyExistsException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

class PublishProjectAction
{
    public function __invoke(string $projectName): void
    {
        if (File::exists($projectName)) {
            throw ProjectAlreadyExistsException::create($projectName);
        }

        File::ensureDirectoryExists("./{$projectName}");

        Process::run("cp -r ../filament-package-skeleton/* ./{$projectName}");
    }
}
