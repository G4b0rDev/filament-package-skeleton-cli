<?php

use App\Actions\Initialize\HandleCustomAssets;
use App\DataTransferObjects\Asset;
use Laravel\Prompts\Key;
use Laravel\Prompts\Prompt;

it('should handle custom assets form', function () {
    Prompt::fake([
        // Question: Do you want to include custom assets?
        Key::ENTER,
        // Question: Do you need css with TailwindCSS setup?
        Key::ENTER,
        // Question: Do you add a custom css file name?
        'custom.css',
        Key::ENTER,
        // Question: Do you need js with AlpineJS setup?
        Key::ENTER,
        // Question: Do you add a custom js file name?
        'custom.js',
        Key::ENTER,
        // Question: Do you need views?
        Key::ENTER,
    ]);

    $asset = app(HandleCustomAssets::class)();

    expect($asset)->toBeInstanceOf(Asset::class);
    expect($asset->withCss)->toBe(true);
    expect($asset->cssName)->toBe('custom.css');
    expect($asset->withJs)->toBe(true);
    expect($asset->jsName)->toBe('custom.js');
    expect($asset->withViews)->toBe(true);
});

it('should handle custom assets form without css and js', function () {
    Prompt::fake([
        // Question: Do you want to include custom assets?
        Key::DOWN,
        Key::ENTER,
    ]);

    $asset = app(HandleCustomAssets::class)();

    expect($asset)->toBeNull();
});
