<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

use Illuminate\Contracts\Support\Arrayable;

readonly class Asset implements Arrayable
{
    public function __construct(
        public bool $withAssets,
        public bool $tailwindcss,
        public ?string $cssName = null,
        public bool $js = false,
        public ?string $jsName = null,
        public bool $views = false,
    ) {
        //
    }

    public static function from(array $data, bool $withAssets): self
    {
        return new self(
            withAssets: $withAssets,
            tailwindcss: $data['tailwindcss'],
            cssName: $data['cssName'] ?? null,
            js: $data['js'] ?? false,
            jsName: $data['jsName'] ?? null,
            views: $data['views'] ?? false,
        );
    }

    public function toArray(): array
    {
        return [
            'withAssets' => $this->withAssets,
            'tailwindcss' => $this->tailwindcss,
            'cssName' => $this->cssName,
            'js' => $this->js,
            'jsName' => $this->jsName,
            'views' => $this->views,
        ];
    }
}
