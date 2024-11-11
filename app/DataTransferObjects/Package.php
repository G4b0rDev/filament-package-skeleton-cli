<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

use Illuminate\Contracts\Support\Arrayable;

readonly class Package implements Arrayable
{
    public function __construct(
        public string $name,
        public string $vendor,
        public bool $standalone,
        public ?Asset $asset,
        public string $testingSetup,
        public array $linters = [],
    ) {
        //
    }

    public static function from(array $data): self
    {
        return new self(
            name: $data['package'],
            vendor: $data['vendor'],
            standalone: $data['standalone'] ?? false,
            asset: ($data['assets']) ? Asset::from($data['assets'], $data['custom_assets']) : null,
            testingSetup: $data['testing'] ?? 'pest',
            linters: $data['linters'] ?? [],
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'vendor' => $this->vendor,
            'standalone' => $this->standalone,
            'testingSetup' => $this->testingSetup,
            'linters' => $this->linters,
        ];
    }
}
