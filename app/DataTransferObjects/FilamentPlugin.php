<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

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
            isStandalone: $data['standalone'] ?? false,
            pluginName: $data['name'] ?? null,
        );
    }
}
