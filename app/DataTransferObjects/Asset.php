<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

/**
 * @property bool $withAssets
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
        public bool $withAssets,
        public bool $withCss = false,
        public ?string $cssName = null,
        public bool $withJs = false,
        public ?string $jsName = null,
        public bool $withViews = false,
    ) {
        //
    }

    public static function from(array $data, bool $withAssets): self
    {
        return new self(
            withAssets: $withAssets,
            withCss: $data['withCss'] ?? false,
            cssName: $data['customCss']['cssName'] ?? null,
            withJs: $data['withJs'] ?? false,
            jsName: $data['customJs']['jsName'] ?? null,
            withViews: $data['withViews'] ?? false,
        );
    }
}
