<?php

declare(strict_types=1);

namespace App\Actions;

use App\Actions\StubHandlers\FilamentPanelBuilderStubHandler;
use App\Actions\StubHandlers\GithubActionsHandler;
use App\Actions\StubHandlers\MetaStubHandler;
use App\Actions\StubHandlers\PublishAssetsHandler;
use App\Actions\StubHandlers\ServiceProviderStubHandler;
use App\DataTransferObjects\Author;
use App\DataTransferObjects\Package;
use App\Facades\Config;
use Illuminate\Support\Str;

class PublishStubs
{
    public function __invoke(Package $package, Author $author): void
    {
        $basePath = Config::basePath().'/'.Str::slug($package->name);

        MetaStubHandler::make($package, $author, $basePath)();
        GithubActionsHandler::make($package, $author, $basePath)();
        ServiceProviderStubHandler::make($package, $author, $basePath)();
        PublishAssetsHandler::make($package, $author, $basePath)();
        FilamentPanelBuilderStubHandler::make($package, $author, $basePath)();
    }
}
