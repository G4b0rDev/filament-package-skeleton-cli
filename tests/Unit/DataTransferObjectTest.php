<?php

use App\DataTransferObjects\Asset;
use App\DataTransferObjects\Author;
use App\DataTransferObjects\Config;
use App\DataTransferObjects\FilamentPlugin;
use App\DataTransferObjects\Package;

it('creates an Author DTO with a valid name and email', function () {
    $data = [
        'name' => fake()->name(),
        'email' => fake()->email(),
    ];

    $author = Author::from($data);

    expect($author)->toBeInstanceOf(Author::class);

    expect($author->name)->toBe($data['name']);
    expect($author->email)->toBe($data['email']);
});

it('creates a FilamentPlugin DTO with specified standalone status and plugin name', function () {
    $data = [
        'isStandalone' => fake()->boolean(),
        'filament' => [
            'pluginName' => fake()->word(),
        ],
    ];

    $filamentPlugin = FilamentPlugin::from($data);

    expect($filamentPlugin)->toBeInstanceOf(FilamentPlugin::class);
    expect($filamentPlugin->isStandalone)->toBe($data['isStandalone']);
    expect($filamentPlugin->pluginName)->toBe($data['filament']['pluginName']);
});

it('creates a Package DTO with specified package details', function () {
    $data = [
        'package' => fake()->word(),
        'vendor' => fake()->word(),
        'filament' => [
            'pluginName' => fake()->word(),
        ],
        'custom_assets' => fake()->boolean(),
    ];

    $package = Package::from($data);

    expect($package)->toBeInstanceOf(Package::class);
    expect($package->name)->toBe($data['package']);
    expect($package->vendor)->toBe($data['vendor']);
    expect($package->filamentPlugin)->toBeInstanceOf(FilamentPlugin::class);
    expect($package->filamentPlugin->pluginName)->toBe($data['filament']['pluginName']);
    expect($package->asset)->toBeNull();
    expect($package->withAssets)->toBe($data['custom_assets']);
});

it('creates an Asset DTO with specified asset details', function () {
    $data = [
        'withCss' => fake()->boolean(),
        'customCss' => [
            'cssName' => fake()->word(),
        ],
        'withJs' => fake()->boolean(),
        'customJs' => [
            'jsName' => fake()->word(),
        ],
        'withViews' => fake()->boolean(),
    ];

    $asset = Asset::from($data);

    expect($asset)->toBeInstanceOf(Asset::class);
    expect($asset->withCss)->toBe($data['withCss']);
    expect($asset->cssName)->toBe($data['customCss']['cssName']);
    expect($asset->withJs)->toBe($data['withJs']);
    expect($asset->jsName)->toBe($data['customJs']['jsName']);
    expect($asset->withViews)->toBe($data['withViews']);
});

it('creates a Config DTO with specified path and vendor name', function () {
    $data = [
        'path' => fake()->word(),
        'vendor_name' => fake()->word(),
    ];

    $config = Config::from($data);

    expect($config)->toBeInstanceOf(Config::class);
    expect($config->path)->toBe($data['path']);
    expect($config->vendorName)->toBe($data['vendor_name']);
    expect($config->toArray())->toBe($data);
    expect($config->toJson())->toBe(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
});