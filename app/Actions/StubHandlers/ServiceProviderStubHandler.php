<?php

declare(strict_types=1);

namespace App\Actions\StubHandlers;

use Binafy\LaravelStub\Facades\LaravelStub;
use Illuminate\Support\Str;

class ServiceProviderStubHandler extends BaseStubHandler
{
    public function __invoke(): void
    {
        $stub = "{$this->basePath}/src/SkeletonServiceProvider.stub";

        $packageName = Str::studly($this->package->name);

        LaravelStub::from($stub)
            ->to("{$this->basePath}/src")
            ->name("{$this->package->name}ServiceProvider")
            ->ext('php')
            ->replaces([
                'NAMESPACE' => $packageName,
                'CLASS' => $packageName,
                'PACKAGE_NAME' => $this->package->name,
                'CSS_ASSET' => $this->package->asset->cssName ?? '',
                'JS_ASSET' => $this->package->asset->jsName ?? '',
                'FILAMENT_PLUGIN_ID' => $packageName,
            ])
            ->conditions([
                'hasAssets' => $this->package->withAssets,
                'hasCss' => $this->package->asset->withCss,
                'hasJs' => $this->package->asset->withJs,
            ])
            ->generate();

        $this->cleanUp($stub);
    }
}
