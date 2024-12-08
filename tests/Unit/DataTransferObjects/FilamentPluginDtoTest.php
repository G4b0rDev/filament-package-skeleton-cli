<?php

use App\DataTransferObjects\FilamentPlugin;

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
