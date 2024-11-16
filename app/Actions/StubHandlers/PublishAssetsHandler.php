<?php

declare(strict_types=1);

namespace App\Actions\StubHandlers;

use Binafy\LaravelStub\Facades\LaravelStub;
use Illuminate\Support\Facades\File;

class PublishAssetsHandler extends BaseStubHandler
{
    public function __invoke(): void
    {
        if (! $this->package->withAssets) {
            return;
        }

        $this->publishTailwind();
        $this->publishCss();
        $this->publishJavaScript();
        $this->publishViews();
        $this->publishViteConfig();
    }

    protected function publishTailwind(): void
    {
        $stub = "{$this->basePath}/tailwindcss.config.js";

        if (! $this->package->asset->withCss) {
            $this->cleanUp($stub);

            return;
        }

        LaravelStub::from($stub)
            ->to($this->basePath)
            ->name('tailwindcss.config')
            ->ext('js')
            ->generate();

        $this->cleanUp($stub);
    }

    protected function publishCss(): void
    {
        if (is_null($this->package->asset->cssName)) {
            $this->cleanUp("{$this->basePath}/resources/css");

            return;
        }

        $fileName = ($this->package->asset->cssName)
            ? $this->package->asset->cssName
            : $this->package->name;

        File::move(
            "{$this->basePath}/resources/css/skeleton.css",
            "{$this->basePath}/resources/css/{$fileName}.css",
        );
    }

    protected function publishJavaScript(): void
    {
        if (! $this->package->asset->withJs) {
            $this->cleanUp("{$this->basePath}/resources/js");

            return;
        }

        $fileName = ($this->package->asset->jsName)
            ? $this->package->asset->jsName
            : $this->package->name;

        File::move(
            "{$this->basePath}/resources/js/skeleton.js",
            "{$this->basePath}/resources/js/{$fileName}.js",
        );
    }

    protected function publishViews(): void
    {
        if ($this->package->asset->withViews) {
            return;
        }

        $this->cleanUp("{$this->basePath}/resources/views");
    }

    protected function publishViteConfig(): void
    {
        $cssFileName = ($this->package->asset->cssName)
            ? $this->package->asset->cssName
            : $this->package->name;

        $jsFileName = ($this->package->asset->jsName)
            ? $this->package->asset->jsName
            : $this->package->name;

        LaravelStub::from("{$this->basePath}/vite.config.js.stub")
            ->to($this->basePath)
            ->name('vite.config')
            ->ext('js')
            ->replaces([
                'JAVASCRIPT' => $jsFileName,
                'STYLESHEET' => $cssFileName,
            ])
            ->conditions(['hasJavascript' => true])
            ->generate();
    }
}
