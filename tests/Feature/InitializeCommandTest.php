<?php

use App\Actions\HandleDependencyInstall;
use App\Actions\PublishProjectAction;
use App\Actions\PublishStubs;
use App\Commands\InitializeCommand;
use App\ConfigHandler;
use App\DataTransferObjects\Asset;
use App\DataTransferObjects\Author;
use App\DataTransferObjects\Package;
use App\Exceptions\ProjectAlreadyExistsException;
use App\Facades\Config;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

beforeEach(function () {
    App::instance(
        ConfigHandler::class,
        new ConfigHandler(configPath: __DIR__.'/../Package/filament-package-skeleton/config.json')
    );

    Config::set('path', __DIR__.'/../Package');
});

afterAll(function () {
    Arr::map(File::directories(__DIR__.'/../Package'), fn (string $directory) => File::deleteDirectory($directory));
});

it('should initialize a new package project without assets', function (array $package, array $author) {
    Process::partialMock()->shouldReceive('concurrently')
        ->andReturn(0);

    $package = Package::from($package);
    $author = Author::from($author);

    $this->artisan('new')
        ->expectsQuestion('Author name', $author->name)
        ->expectsQuestion('Author email', $author->email)
        ->expectsQuestion('Package name', $package->name)
        ->expectsQuestion('Vendor name', $package->vendor)
        ->expectsConfirmation('Do you want to create a standalone filament package?', $package->filamentPlugin->isStandalone)
        ->expectsConfirmation('Do you want have custom assets?', $package->withAssets)
        ->assertExitCode(0);
})->with('defaultPackage');

it('should throw an expection if project directory already exists', function (array $package, array $author) {
    Process::partialMock()->shouldReceive('concurrently')
        ->andReturn(0);

    $package = Package::from($package);
    $author = Author::from($author);

    $this->artisan('new')
        ->expectsQuestion('Author name', $author->name)
        ->expectsQuestion('Author email', $author->email)
        ->expectsQuestion('Package name', $package->name)
        ->expectsQuestion('Vendor name', $package->vendor)
        ->expectsConfirmation('Do you want to create a standalone filament package?', $package->filamentPlugin->isStandalone)
        ->expectsConfirmation('Do you want have custom assets?', $package->withAssets)
        ->assertExitCode(0);
})
    ->with('defaultPackage')
    ->throws(ProjectAlreadyExistsException::class);

it('should initialize a new package project', function (array $package, array $author, array $asset) {
    Process::partialMock()->shouldReceive('concurrently')
        ->andReturn(0);

    $asset = Asset::from($asset);
    $package = Package::from([...$package, 'assets' => $asset]);
    $author = Author::from($author);

    $this->artisan('new')
        ->expectsQuestion('Author name', $author->name)
        ->expectsQuestion('Author email', $author->email)
        ->expectsQuestion('Package name', $package->name.'2')
        ->expectsQuestion('Vendor name', $package->vendor)
        ->expectsConfirmation('Do you want to create a standalone filament package?', $package->filamentPlugin->isStandalone)
        ->expectsConfirmation('Do you want have custom assets?', 'yes')
        ->expectsConfirmation('Do you need css with TailwindCSS setup?', 'yes')
        ->expectsQuestion('Do you add a custom css file name?', $package->asset->cssName)
        ->expectsConfirmation('Do you need js with AlpineJS setup?', 'yes')
        ->expectsQuestion('Do you add a custom js file name?', $package->asset->jsName)
        ->expectsConfirmation('Do you need blade templates/views?', 'yes')
        ->assertExitCode(0);
})->with('packageWithAsset');

it('should validate slug value correctly', function () {
    $command = new InitializeCommand(
        new PublishProjectAction,
        new PublishStubs,
        new HandleDependencyInstall
    );

    expect($command->validateSlugValue('package name', 'valid-slug'))->toBeNull();
    expect($command->validateSlugValue('package name', 'Invalid Slug'))->toBe('The package name format is invalid.');
    expect($command->validateSlugValue('package name', 'invalid_slug'))->toBe('The package name format is invalid.');
    expect($command->validateSlugValue('package name', 'invalid-slug-'))->toBe('The package name format is invalid.');
    expect($command->validateSlugValue('package name', 'invalid--slug'))->toBe('The package name format is invalid.');
});
