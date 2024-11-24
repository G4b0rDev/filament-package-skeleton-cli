<?php

use App\Actions\StubHandlers\PublishAssetsHandler;
use App\DataTransferObjects\Asset;
use App\DataTransferObjects\Author;
use App\DataTransferObjects\FilamentPlugin;
use App\DataTransferObjects\Package;
use Binafy\LaravelStub\Facades\LaravelStub;
use Binafy\LaravelStub\Providers\LaravelStubServiceProvider;
use Illuminate\Filesystem\Filesystem;
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
            'withViews' => false,
        ]),
        withAssets: false,
    );
});

afterEach(function () {
    File::swap(new Filesystem);
});

it('should not publish tailwindcss config', function (Package $package) {
    $tailwindPath = "{$this->basePath}/tailwindcss.config.js";

    File::shouldReceive('isFile')
        ->with($tailwindPath)
        ->andReturnTrue();

    File::shouldReceive('exists')
        ->with($tailwindPath)
        ->andReturnFalse();

    File::shouldReceive('delete')
        ->with($tailwindPath)
        ->andReturnTrue();

    PublishAssetsHandler::make($package, $this->author)();
    expect(File::exists($tailwindPath))->toBeFalse();
})->with([
    fn () => new Package(
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
        withAssets: true,
    ),
]);

it('should publish tailwindcss config', function (Package $package) {
    $tailwindPath = "{$this->basePath}/tailwindcss.config.js";

    File::shouldReceive('exists')
        ->with($tailwindPath)
        ->once()
        ->andReturnTrue();

    PublishAssetsHandler::make($package, $this->author)();

    expect(File::exists($tailwindPath))->toBeTrue();
})->with([
    fn () => new Package(
        name: str()->studly('test-package'),
        vendor: 'test-vendor',
        filamentPlugin: FilamentPlugin::from([
            'isStandalone' => false,
            'pluginName' => 'Test',
        ]),
        asset: Asset::from([
            'withCss' => true,
            'withJs' => false,
        ]),
        withAssets: true,
    ),
]);

it('should not publish the css', function (Package $package) {
    $cssPath = "{$this->basePath}/resources/css";

    PublishAssetsHandler::make($package, $this->author)();

    File::shouldReceive('isDirectory')
        ->with($cssPath)
        ->andReturnTrue();

    File::shouldReceive('exists')
        ->with($cssPath)
        ->andReturnTrue();

    File::shouldReceive('deleteDirectory')
        ->with($cssPath)
        ->andReturnTrue();

    expect(File::exists($cssPath))->toBeTrue();
})->with([
    fn () => new Package(
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
        withAssets: true,
    ),
]);

it('should publish and rename the css file', function (Package $package) {
    PublishAssetsHandler::make($package, $this->author)();

    $oldCssPath = "{$this->basePath}/resources/css/skeleton.css";
    $cssPath = "{$this->basePath}/resources/css/TestPackage.css";

    File::shouldReceive('move')
        ->with($oldCssPath, $cssPath)
        ->andReturnFalse();

    File::shouldReceive('exists')
        ->with($cssPath)
        ->andReturnTrue();

    expect(File::exists($cssPath))->toBeTrue();
})->with([
    fn () => new Package(
        name: str()->studly('test-package'),
        vendor: 'test-vendor',
        filamentPlugin: FilamentPlugin::from([
            'isStandalone' => false,
            'pluginName' => 'Test',
        ]),
        asset: Asset::from([
            'withCss' => true,
            'withJs' => false,
        ]),
        withAssets: true,
    ),
]);

it('should publish and rename custom css file', function (Package &$package) {
    PublishAssetsHandler::make($package, $this->author)();

    $oldCssPath = "{$this->basePath}/resources/css/skeleton.css";
    $cssPath = "{$this->basePath}/resources/css/app.css";

    File::shouldReceive('move')
        ->with($oldCssPath, $cssPath)
        ->andReturnFalse();

    File::shouldReceive('exists')
        ->with($cssPath)
        ->andReturnTrue();

    expect(File::exists($cssPath))->toBeTrue();
})->with([
    fn () => new Package(
        name: str()->studly('test-package'),
        vendor: 'test-vendor',
        filamentPlugin: FilamentPlugin::from([
            'isStandalone' => false,
            'pluginName' => 'Test',
        ]),
        asset: Asset::from([
            'withCss' => true,
            'customCss' => [
                'cssName' => 'app',
            ],
            'withJs' => false,
        ]),
        withAssets: true,
    ),
]);

it('should not publish javascript', function (Package $package) {
    $jsPath = "{$this->basePath}/resources/js";

    File::shouldReceive('isDirectory')
        ->with($jsPath)
        ->andReturnTrue();

    File::shouldReceive('exists')
        ->with($jsPath)
        ->andReturnFalse();

    File::shouldReceive('deleteDirectory')
        ->with($jsPath)
        ->andReturnTrue();

    PublishAssetsHandler::make($package, $this->author)();
    expect(File::exists($jsPath))->toBeFalse();
})->with([
    fn () => new Package(
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
        withAssets: true,
    ),
]);

it('should publish javascript', function (Package $package) {
    $oldJsPath = "{$this->basePath}/resources/css/skeleton.js";
    $jsPath = "{$this->basePath}/resources/css/TestPackage.js";

    File::shouldReceive('move')
        ->with($oldJsPath, $jsPath)
        ->andReturnFalse();

    File::shouldReceive('exists')
        ->with($oldJsPath)
        ->andReturnFalse();

    File::shouldReceive('exists')
        ->with($jsPath)
        ->andReturnTrue();

    PublishAssetsHandler::make($package, $this->author)();
    expect(File::exists($oldJsPath))->toBeFalse();
    expect(File::exists($jsPath))->toBeTrue();
})->with([
    fn () => new Package(
        name: str()->studly('test-package'),
        vendor: 'test-vendor',
        filamentPlugin: FilamentPlugin::from([
            'isStandalone' => false,
            'pluginName' => 'Test',
        ]),
        asset: Asset::from([
            'withCss' => false,
            'withJs' => true,
        ]),
        withAssets: true,
    ),
]);

it('should publish javascript with custom name', function (Package $package) {
    $oldJsPath = "{$this->basePath}/resources/css/skeleton.js";
    $jsPath = "{$this->basePath}/resources/css/app.js";

    File::shouldReceive('move')
        ->with($oldJsPath, $jsPath)
        ->andReturnFalse();

    File::shouldReceive('exists')
        ->with($oldJsPath)
        ->andReturnFalse();

    File::shouldReceive('exists')
        ->with($jsPath)
        ->andReturnTrue();

    PublishAssetsHandler::make($package, $this->author)();
    expect(File::exists($oldJsPath))->toBeFalse();
    expect(File::exists($jsPath))->toBeTrue();
})->with([
    fn () => new Package(
        name: str()->studly('test-package'),
        vendor: 'test-vendor',
        filamentPlugin: FilamentPlugin::from([
            'isStandalone' => false,
            'pluginName' => 'Test',
        ]),
        asset: Asset::from([
            'withCss' => false,
            'withJs' => true,
            'customJs' => [
                'jsName' => 'app',
            ],
        ]),
        withAssets: true,
    ),
]);

it('should not publish the views', function (Package $package) {
    $viewsPath = "{$this->basePath}/resources/views";

    File::shouldReceive('isDirectory')
        ->with($viewsPath)
        ->andReturnTrue();

    File::shouldReceive('exists')
        ->with($viewsPath)
        ->andReturnFalse();

    File::shouldReceive('deleteDirectory')
        ->with($viewsPath)
        ->andReturnTrue();

    PublishAssetsHandler::make($package, $this->author)();
    expect(File::exists($viewsPath))->toBeFalse();
})->with([
    fn () => new Package(
        name: str()->studly('test-package'),
        vendor: 'test-vendor',
        filamentPlugin: FilamentPlugin::from([
            'isStandalone' => false,
            'pluginName' => 'Test',
        ]),
        asset: Asset::from([
            'withCss' => false,
            'withJs' => false,
            'withViews' => false,
        ]),
        withAssets: true,
    ),
]);

it('should publish the views', function (Package $package) {
    $viewsPath = "{$this->basePath}/resources/views";

    File::shouldReceive('exists')
        ->with($viewsPath)
        ->andReturnTrue();

    PublishAssetsHandler::make($package, $this->author)();
    expect(File::exists($viewsPath))->toBeTrue();
})->with([
    fn () => new Package(
        name: str()->studly('test-package'),
        vendor: 'test-vendor',
        filamentPlugin: FilamentPlugin::from([
            'isStandalone' => false,
            'pluginName' => 'Test',
        ]),
        asset: Asset::from([
            'withCss' => false,
            'withJs' => false,
            'withViews' => true,
        ]),
        withAssets: true,
    ),
]);

it('should should publish vite config', function () {
    $vitePath = "{$this->basePath}/vite.config.js";
    $viteStubPath = __DIR__ . '/../../stubs/vite.config.js';

    File::shouldReceive('exists')
        ->with($vitePath)
        ->andReturnTrue();

    File::shouldReceive('exists')
        ->with($viteStubPath)
        ->andReturnFalse();

    PublishAssetsHandler::make($this->package, $this->author)();

    expect(File::exists($vitePath))->toBeTrue();
    expect(File::exists($viteStubPath))->toBeFalse();
})->with([
    fn () => new Package(
        name: str()->studly('test-package'),
        vendor: 'test-vendor',
        filamentPlugin: FilamentPlugin::from([
            'isStandalone' => false,
            'pluginName' => 'Test',
        ]),
        asset: Asset::from([
            'withCss' => true,
            'withJs' => false,
        ]),
        withAssets: true,
    ),
]);
