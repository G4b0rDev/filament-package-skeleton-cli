<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

use Illuminate\Support\Str;

/**
 * @property string $name
 * @property string $vendor
 * @property FilamentPlugin $filamentPlugin
 * @property Asset $asset
 * @property string $testingSetup
 * @property array $codeStyle
 *
 * @method static Package from(array $data): self
 */
readonly class Package
{
    public function __construct(
        public string $name,
        public string $vendor,
        public FilamentPlugin $filamentPlugin,
        public Asset $asset,
        public string $testingSetup,
        public array $codeStyle = [],
    ) {
        //
    }

    public static function from(array $data): self
    {
        return new self(
            name: Str::slug($data['package']),
            vendor: Str::slug($data['vendor']),
            filamentPlugin: FilamentPlugin::from($data['filament']),
            asset: $data['assets'],
            testingSetup: $data['testing'] ?? 'pest',
            codeStyle: $data['codeStyle'] ?? [],
        );
    }
}
