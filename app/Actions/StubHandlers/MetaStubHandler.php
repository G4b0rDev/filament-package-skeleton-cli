<?php

declare(strict_types=1);

namespace App\Actions\StubHandlers;

use Binafy\LaravelStub\Facades\LaravelStub;
use Illuminate\Support\Str;

class MetaStubHandler extends BaseStubHandler
{
    public function __invoke(): void
    {
        $this->publishReadme();
        $this->publishChangelog();
        $this->publishLicense();
        $this->publishComposer();
    }

    protected function publishReadme(): void
    {
        $stub = "{$this->basePath}/README.md";

        LaravelStub::from($stub)
            ->to($this->basePath)
            ->name('README')
            ->ext('md')
            ->generate();
    }

    protected function publishChangelog(): void
    {
        $stub = "{$this->basePath}/CHANGELOG.md";

        LaravelStub::from($stub)
            ->to($this->basePath)
            ->name('CHANGELOG')
            ->ext('md')
            ->replace('PACKAGE_NAME', $this->package->name)
            ->generate();
    }

    protected function publishLicense(): void
    {
        $stub = "{$this->basePath}/LICENSE.md";

        LaravelStub::from($stub)
            ->to($this->basePath)
            ->name('LICENSE')
            ->ext('md')
            ->replaces([
                'VENDOR_NAME' => $this->author->name,
                'AUTHOR_EMAIL' => $this->author->email,
            ])
            ->generate();
    }

    protected function publishComposer(): void
    {
        $stub = "{$this->basePath}/composer.json";

        LaravelStub::from($stub)
            ->to($this->basePath)
            ->name('composer')
            ->ext('json')
            ->replaces([
                'VENDOR_NAME' => Str::slug($this->package->vendor),
                'PACKAGE_NAME' => Str::slug($this->package->name),
                'PACKAGE_DESCRIPTION' => $this->package->description ?? '',
                'AUTHOR_NAME' => $this->author->name,
                'AUTHOR_EMAIL' => $this->author->email,
                'NAMESPACE' => Str::studly($this->package->name),
            ])
            ->generate();
    }
}
