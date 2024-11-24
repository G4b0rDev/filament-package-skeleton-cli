<?php

use App\Actions\StubHandlers\FilamentPanelBuilderStubHandler;
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

    $this->basePath = getcwd().'/TestPackage';

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

it('should not publish the filament panel class if it\'s disabled', function () {
    $pluginPath = "{$this->basePath}/src/TestPlugin.php";
    $skeletonPath = "{$this->basePath}/src/SkeletonPlugin.stub";

    File::shouldReceive('isFile')
        ->with($skeletonPath)
        ->once()
        ->andReturnTrue();

    File::shouldReceive('delete')
        ->with($skeletonPath)
        ->andReturnTrue();

    File::shouldReceive('exists')
        ->with($pluginPath)
        ->andReturnFalse();

    File::shouldReceive('exists')
        ->with($skeletonPath)
        ->andReturnFalse();

    FilamentPanelBuilderStubHandler::make($this->package, $this->author)();

    expect(File::exists($pluginPath))->toBeFalse();
    expect(File::exists($skeletonPath))->toBeFalse();
});

it('should publish the filament panel class', function () {
    $pluginPath = "{$this->basePath}/src/TestPackagePlugin.php";
    $skeletonPath = "{$this->basePath}/src/SkeletonPlugin.stub";

    $package = new Package(
        name: 'test-package',
        vendor: 'test-vendor',
        filamentPlugin: FilamentPlugin::from([
            'isStandalone' => true,
            'filament' => [
                'pluginName' => 'Test',
            ],
        ]),
        asset: Asset::from([
            'withCss' => false,
            'withJs' => false,
        ]),
        withAssets: false,
    );

    File::shouldReceive('isFile')
        ->with($skeletonPath)
        ->andReturnTrue();

    File::shouldReceive('delete')
        ->with($skeletonPath)
        ->andReturnTrue();

    File::shouldReceive('exists')
        ->with($pluginPath)
        ->andReturnTrue();

    File::shouldReceive('exists')
        ->with($skeletonPath)
        ->andReturnFalse();

    FilamentPanelBuilderStubHandler::make($package, $this->author)();

    expect(File::exists($pluginPath))->toBeTrue();
    expect(File::exists($skeletonPath))->toBeFalse();
});
