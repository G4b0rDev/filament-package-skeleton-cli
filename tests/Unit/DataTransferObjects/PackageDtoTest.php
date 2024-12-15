<?php

use App\DataTransferObjects\FilamentPlugin;
use App\DataTransferObjects\Package;

it('creates a Package DTO with specified package details', function () {
    $data = [
        'package' => fake()->word(),
        'vendor' => fake()->word(),
        'isStandalone' => fake()->boolean(),
        'filament' => [
            'pluginName' => fake()->word(),
        ],
        'assets' => null,
    ];

    $package = Package::from($data);

    expect($package)->toBeInstanceOf(Package::class);
    expect($package->name)->toBe($data['package']);
    expect($package->vendor)->toBe($data['vendor']);
    expect($package->filamentPlugin)->toBeInstanceOf(FilamentPlugin::class);
    expect($package->filamentPlugin->pluginName)->toBe($data['filament']['pluginName']);
    expect($package->asset)->toBeNull();
    expect($package->withAssets)->toBe(! is_null($package->asset));
});
