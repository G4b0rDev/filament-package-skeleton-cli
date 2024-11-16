<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

/**
 * @property bool $withCss
 * @property string|null $cssName
 * @property bool $withJs
 * @property string|null $jsName
 * @property bool $withViews
 *
 * @method static Asset from(array $data, bool $withAssets): self
 */
readonly class Asset
{
    public function __construct(
        public bool $withCss = false,
        public ?string $cssName = null,
        public bool $withJs = false,
        public ?string $jsName = null,
        public bool $withViews = false,
    ) {
        //
    }

    public static function from(array $data): self
    {
        return new self(
            withCss: $data['withCss'] ?? false,
            cssName: $data['customCss']['cssName'] ?? null,
            withJs: $data['withJs'] ?? false,
            jsName: $data['customJs']['jsName'] ?? null,
            withViews: $data['withViews'] ?? false,
        );
    }
}
