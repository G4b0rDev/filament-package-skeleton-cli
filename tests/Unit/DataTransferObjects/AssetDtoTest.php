<?php

use App\DataTransferObjects\Asset;

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
