<?php

use App\Actions\StubHandlers\MetaStubHandler;
use App\DataTransferObjects\Asset;
use App\DataTransferObjects\Author;
use App\DataTransferObjects\FilamentPlugin;
use App\DataTransferObjects\Package;
use Binafy\LaravelStub\Facades\LaravelStub;
use Binafy\LaravelStub\Providers\LaravelStubServiceProvider;
use Illuminate\Filesystem\FilesystemServiceProvider;
use Illuminate\Support\Facades\File;

beforeEach(function () {
    app()->register(LaravelStubServiceProvider::class);
    app()->register(FilesystemServiceProvider::class);

    File::spy();
    LaravelStub::spy();

    $this->basePath = getcwd() . '/TestPackage';

    $this->author = Author::from([
        'name' => 'Test Author',
        'email' => 'test@example.com',
    ]);

    $this->package = new Package(
        name: str()->studly('test-package'),
        vendor: 'test-vendor',
        filamentPlugin: FilamentPlugin::from([
            'isStandalone' => false,
            'pluginName' => 'Test',
        ]),
        asset: Asset::from([
            'withCss' => false,
            'withJs' => false,
        ]),
        withAssets: false,
    );
});

it('should publish the readme', function () {
    $readmePath = "{$this->basePath}/README.md";

    File::shouldReceive('exists')
        ->with($readmePath)
        ->once()
        ->andReturnTrue();

    MetaStubHandler::make($this->package, $this->author)();

    expect(File::exists($readmePath))->toBeTrue();
});

it('should publish the changelog', function () {
    $changelogPath = "{$this->basePath}/CHANGELOG.md";

    File::shouldReceive('exists')
        ->with($changelogPath)
        ->once()
        ->andReturnTrue();

    File::shouldReceive('get')
        ->with($changelogPath)
        ->once()
        ->andReturn('All notable changes to `TestPackage` will be documented in this file.');

    MetaStubHandler::make($this->package, $this->author)();

    expect(File::exists($changelogPath))->toBeTrue();
    expect(File::get($changelogPath))->toContain($this->package->name);
});

it('should publish the license', function () {
    $licensePath = "{$this->basePath}/LICENSE.md";

    File::shouldReceive('exists')
        ->with($licensePath)
        ->once()
        ->andReturnTrue();

    File::shouldReceive('get')
        ->with($licensePath)
        ->once()
        ->andReturn("Copyright (c) {$this->author->name} <{$this->author->email}>");

    MetaStubHandler::make($this->package, $this->author)();

    expect(File::exists($licensePath))->toBeTrue();
    expect(File::get($licensePath))->toContain("Copyright (c) {$this->author->name} <{$this->author->email}>");
});

it('should publish the composer', function () {
    $composerPath = "{$this->basePath}/composer.json";

    File::shouldReceive('exists')
        ->with($composerPath)
        ->once()
        ->andReturnTrue();

    MetaStubHandler::make($this->package, $this->author)();

    expect(File::exists($composerPath))->toBeTrue();
});

it('should publish the readme with the correct content', function () {
    $readmePath = "{$this->basePath}/README.md";

    File::shouldReceive('exists')
        ->with($readmePath)
        ->once()
        ->andReturnTrue();

    File::shouldReceive('get')
        ->with($readmePath)
        ->once()
        ->andReturn('This is the README for TestPackage.');

    MetaStubHandler::make($this->package, $this->author)();

    expect(File::exists($readmePath))->toBeTrue();
    expect(File::get($readmePath))->toContain('TestPackage');
});
