<?php

use App\ConfigHandler;
use App\Facades\Config;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;

beforeEach(function () {
    App::instance(
        ConfigHandler::class,
        new ConfigHandler(configPath: __DIR__ . '/../Package/filament-package-skeleton/config.json')
    );

    Config::set('path', __DIR__ . '/../Package');

    Arr::map(File::directories(__DIR__ . '/../Package'), fn (string $directory) => File::deleteDirectory($directory));

    $packages = [
        [
            'package' => 'my-simple-plugin',
            'vendor' => 'test-user',
            'isStandalone' => true,
            'asset' => [
                'withCss' => true,
                'customCss' => ['cssName' => 'app'],
                'withJs' => true,
                'customJs' => ['jsName' => 'app'],
                'withViews' => true,
            ],
        ],
        [
            'package' => 'my-simple-plugin2',
            'vendor' => 'test-user',
            'isStandalone' => true,
            'asset' => [
                'withCss' => false,
                'withJs' => false,
                'withViews' => false,
            ],
        ],
    ];

    $author = [
        'name' => 'Test User',
        'email' => 'test@example.com',
    ];

    foreach ($packages as $package) {
        generatePackage($package, $author, $package['asset']);
    }
});

afterAll(function () {
    File::deleteDirectory(__DIR__ . '/../Package/my-simple-plugin');
});

it('should publish tailwindcss config and base css', function () {
    expect(file_exists(__DIR__ . '/../Package/my-simple-plugin/tailwindcss.config.js'))->toBeTrue();
    expect(file_exists(__DIR__ . '/../Package/my-simple-plugin/resources/css'))->toBeTrue();
    expect(file_exists(__DIR__ . '/../Package/my-simple-plugin/resources/css/app.css'))->toBeTrue();
});

it('should not publish tailwindcss config and base css if no assets are set', function () {
    expect(file_exists(__DIR__ . '/../Package/my-simple-plugin2/tailwindcss.config.js'))->toBeFalse();
    expect(file_exists(__DIR__ . '/../Package/my-simple-plugin2/resources/css'))->toBeFalse();
    expect(file_exists(__DIR__ . '/../Package/my-simple-plugin2/resources/css/app.css'))->toBeFalse();
});

it('should publish javascript skeleton', function () {
    expect(file_exists(__DIR__ . '/../Package/my-simple-plugin/resources/js'))->toBeTrue();
    expect(file_exists(__DIR__ . '/../Package/my-simple-plugin/resources/js/app.js'))->toBeTrue();
});

it('should set the default javascript file name as the package name if no custom name is set', function () {
    $asset = [
        'withCss' => true,
        'withJs' => true,
    ];

    $package = [
        'package' => 'my-simple-plugin3',
        'vendor' => 'test-user',
        'isStandalone' => false,
        'asset' => $asset,
    ];

    $author = [
        'name' => 'Test User',
        'email' => 'test@example.com',
    ];

    generatePackage($package, $author, $asset);

    expect(file_exists(__DIR__ . '/../Package/my-simple-plugin3/resources/js/my-simple-plugin3.js'))->toBeTrue();
});

it('should publish the views skeleton', function () {
    expect(file_exists(__DIR__ . '/../Package/my-simple-plugin/resources/views'))->toBeTrue();
});

it('should publish vite config', function () {
    expect(file_exists(__DIR__ . '/../Package/my-simple-plugin/vite.config.js'))->toBeTrue();
});
