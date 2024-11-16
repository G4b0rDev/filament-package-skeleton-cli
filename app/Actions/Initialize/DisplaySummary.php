<?php

declare(strict_types=1);

namespace App\Actions\Initialize;

use App\DataTransferObjects\Author;
use App\DataTransferObjects\Package;

use function Laravel\Prompts\intro;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\table;

class DisplaySummary
{
    public function __invoke(Author $author, Package $package): void
    {
        intro('Summary author');
        table(
            headers: ['Author', 'Email'],
            rows: [
                [$author->name, $author->email],
            ],
        );

        intro('Summary package');
        $isStandalone = $package->filamentPlugin->isStandalone ? '✅' : '❌';
        $hasAssets = $package->withAssets ? '✅' : '❌';
        $hasViews = ($package->withAssets && $package->asset->withViews) ? '✅' : '❌';

        table(
            headers: ['Package', 'Vendor', 'Standalone', 'Custom Assets', 'Views'],
            rows: [
                [$package->name, $package->vendor, $isStandalone, $hasAssets, $hasViews],
            ]
        );

        spin(
            message: 'let me think... 🤔',
            callback: fn () => sleep(1),
        );
    }
}
