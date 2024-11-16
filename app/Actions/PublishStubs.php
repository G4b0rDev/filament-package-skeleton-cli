<?php

declare(strict_types=1);

namespace App\Actions;

use App\Actions\StubHandlers\FilamentPanelBuilderStubHandler;
use App\Actions\StubHandlers\MetaStubHandler;
use App\Actions\StubHandlers\PublishAssetsHandler;
use App\Actions\StubHandlers\ServiceProviderStubHandler;
use App\DataTransferObjects\Author;
use App\DataTransferObjects\Package;

class PublishStubs
{
    public function __invoke(Package $package, Author $author): void
    {
        MetaStubHandler::make($package, $author)();
        ServiceProviderStubHandler::make($package, $author)();
        PublishAssetsHandler::make($package, $author)();
        FilamentPanelBuilderStubHandler::make($package, $author)();
    }
}
