<?php

declare(strict_types=1);

namespace App\Actions\StubHandlers;

use Binafy\LaravelStub\Facades\LaravelStub;
use Illuminate\Support\Str;

class FilamentPanelBuilderStubHandler extends BaseStubHandler
{
    public function __invoke(): void
    {
        $stub = "{$this->basePath}/src/SkeletonPlugin.stub";

        if (! $this->package->filamentPlugin->isStandalone) {
            $this->cleanUp($stub);
        }

        LaravelStub::from($stub)
            ->to($this->basePath)
            ->name("{$this->package->name}Plugin")
            ->ext('php')
            ->replaces([
                'NAMESPACE' => Str::studly($this->package->name),
                'FILAMENT_PLUGIN' => Str::studly($this->package->name),
                'FILAMENT_PLUGIN_ID' => Str::slug($this->package->name),
            ])
            ->generate();

        $this->cleanUp($stub);
    }
}
