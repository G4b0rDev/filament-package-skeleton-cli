<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class Config implements Arrayable, Jsonable
{
    public function __construct(
        public ?string $path = null,
        public ?string $vendorName = null,
    ) {
        //
    }

    public static function from(array $data): self
    {
        return new self(
            path: $data['path'] ?? null,
            vendorName: $data['vendor_name'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'path' => $this->path,
            'vendor_name' => $this->vendorName,
        ];
    }

    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), $options | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
}
