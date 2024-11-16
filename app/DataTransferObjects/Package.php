<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

use App\Enums\CodeStyle;
use App\Enums\TestingFramework;
use Illuminate\Support\Str;

/**
 * @property string $name
 * @property string $vendor
 * @property FilamentPlugin $filamentPlugin
 * @property Asset $asset
 * @property TestingFramework $testingFramework
 * @property CodeStyle[] $codeStyle
 *
 * @method static Package from(array $data): self
 */
readonly class Package
{
    public function __construct(
        public string $name,
        public string $vendor,
        public FilamentPlugin $filamentPlugin,
        public ?Asset $asset,
        public bool $withAssets,
        public TestingFramework $testingFramework,
        public array $codeStyle = [],
    ) {
        //
    }

    public static function from(array $data): self
    {
        return new self(
            name: Str::slug($data['package']),
            vendor: Str::slug($data['vendor']),
            filamentPlugin: FilamentPlugin::from($data),
            asset: $data['assets'] ?? null,
            withAssets: $data['custom_assets'] ?? false,
            testingFramework: TestingFramework::tryFrom($data['testing']) ?? TestingFramework::Pest,
            codeStyle: array_map(fn ($value) => CodeStyle::from($value), $data['codeStyle'] ?? []),
        );
    }
}
