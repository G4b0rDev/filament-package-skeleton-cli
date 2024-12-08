<?php

use App\DataTransferObjects\Config;

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
