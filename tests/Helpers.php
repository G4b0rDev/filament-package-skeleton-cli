<?php

use App\Actions\PublishProjectAction;
use App\Actions\PublishStubs;
use App\DataTransferObjects\Asset;
use App\DataTransferObjects\Author;
use App\DataTransferObjects\Package;

function generatePackage(array $package, array $author, array $asset = []): void
{
    if (! empty($asset)) {
        $package['assets'] = Asset::from($asset);
    }

    $package = Package::from($package);
    $author = Author::from($author);

    app(PublishProjectAction::class)($package->name);
    app(PublishStubs::class)($package, $author);
}
