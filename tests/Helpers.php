<?php

use App\Actions\PublishProjectAction;
use App\Actions\PublishStubs;
use App\DataTransferObjects\Asset;
use App\DataTransferObjects\Author;
use App\DataTransferObjects\Package;

function generatePackage(array $package, array $author, ?array $asset = null): void
{
    $package['assets'] = (! is_null($asset) && ! empty($asset))
        ? Asset::from($asset)
        : null;

    $package = Package::from($package);
    $author = Author::from($author);

    app(PublishProjectAction::class)($package->name);
    app(PublishStubs::class)($package, $author);
}
