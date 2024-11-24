<?php

use App\Actions\StubHandlers\ServiceProviderStubHandler;
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

it('should publish the service provider', function () {
    $providerPath = "{$this->basePath}/src/TestPackageServiceProvider.php";
    $skeletonPath = "{$this->basePath}/src/SkeletonServiceProvider.stub";

    File::shouldReceive('isFile')
        ->with($skeletonPath)
        ->once()
        ->andReturnTrue();

    File::shouldReceive('delete')
        ->with($skeletonPath)
        ->once()
        ->andReturnTrue();

    LaravelStub::partialMock()
        ->shouldReceive('generate')
        ->once()
        ->andReturnTrue();

    File::shouldReceive('exists')
        ->with($providerPath)
        ->andReturnTrue();

    File::shouldReceive('exists')
        ->with($skeletonPath)
        ->andReturnFalse();

    ServiceProviderStubHandler::make($this->package, $this->author)();

    expect(File::exists($providerPath))->toBeTrue();
    expect(File::exists($skeletonPath))->toBeFalse();
});
