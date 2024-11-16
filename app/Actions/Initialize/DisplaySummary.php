<?php

declare(strict_types=1);

namespace App\Actions\Initialize;

use App\DataTransferObjects\Author;
use App\DataTransferObjects\Package;
use App\Enums\CodeStyle;

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
        $codeStyle = array_map(fn (CodeStyle $codeStyle) => $codeStyle->value, $package->codeStyle);

        table(
            headers: ['Package', 'Vendor', 'Standalone', 'Custom Assets', 'Views'],
            rows: [
                [$package->name, $package->vendor, $isStandalone, $hasAssets, $hasViews],
            ]
        );

        intro('Summary dependencies');
        table(
            headers: ['Testing Framework', 'Code Style'],
            rows: [
                [$package->testingFramework->name, implode(', ', $codeStyle)],
            ],
        );

        spin(
            message: 'let me think... 🤔',
            callback: fn () => sleep(1),
        );
    }
}
