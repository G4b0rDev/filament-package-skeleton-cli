<?php

use App\DataTransferObjects\FilamentPlugin;
use App\DataTransferObjects\Package;

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
