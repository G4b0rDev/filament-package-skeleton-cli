<?php

use App\Actions\Initialize\HandlePackageInformation;
use App\DataTransferObjects\Package;
use App\Facades\Config;
use Laravel\Prompts\Key;
use Laravel\Prompts\Prompt;

it('can handle package information', function () {
    Config::shouldReceive('get')
        ->with('vendorName')
        ->andReturn('');

    Prompt::fake([
        // Question: Package name
        Key::ENTER,

        // Question: Vendor name
        'g4b0rdev',
        Key::ENTER,

        // Question: Do you want to create a standalone filament package?
        Key::DOWN,
        Key::ENTER,

        // Question: Do you want have custom assets?
        Key::DOWN,
        KEY::ENTER,
    ]);

    $package = app(HandlePackageInformation::class)('test', true);

    expect($package)->toBeInstanceOf(Package::class);
    expect($package->name)->toBe('test');
    expect($package->vendor)->toBe('g4b0rdev');
    expect($package->asset)->toBeNull();
    expect($package->withAssets)->toBeFalse();
});

it('validates the slug value correctly', function () {
    $handler = app(HandlePackageInformation::class);

    $testCases = [
        // [attribute, value, expectedResult]
        ['package name', '', 'The package name is required.'],
        ['vendor name', '', 'The vendor name is required.'],
        ['package name', 'valid-package', null],
        ['vendor name', 'validvendor', null],
        ['package name', 'Invalid_Package!', 'The package name format is invalid.'],
        ['vendor name', 'InvalidVendor!', 'The vendor name format is invalid.'],
        ['package name', 'another-valid-package', null],
        ['vendor name', 'another-valid-vendor', null],
        ['package name', 'UPPERCASE', 'The package name format is invalid.'],
        ['vendor name', 'with spaces', 'The vendor name format is invalid.'],
        ['package name', '123', null],
        ['vendor name', 'hyphens-are-okay', null],
    ];

    foreach ($testCases as [$attribute, $value, $expected]) {
        $result = $handler->validateSlugValue($attribute, $value);

        $message = "Failed asserting that validation of '{$attribute}' with value '{$value}' returns '{$expected}'.";

        expect($result)->toBe($expected, $message);
    }
});
