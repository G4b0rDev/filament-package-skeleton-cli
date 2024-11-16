<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

/**
 * @property-read bool $isStandalone
 * @property-read string|null $pluginName
 *
 * @method static FilamentPlugin from(array $data)
 */
readonly class FilamentPlugin
{
    public function __construct(
        public bool $isStandalone,
        public ?string $pluginName = null,
    ) {
        //
    }

    public static function from(array $data): self
    {
        return new self(
            isStandalone: $data['isStandalone'] ?? false,
            pluginName: $data['filament']['pluginName'] ?? null,
        );
    }
}
