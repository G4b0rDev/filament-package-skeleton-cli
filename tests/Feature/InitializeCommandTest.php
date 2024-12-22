<?php

use App\ConfigHandler;
use App\Exceptions\ProjectAlreadyExistsException;
use App\Facades\Config;
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
    File::deleteDirectory(__DIR__.'/../Package/test-package');
    File::deleteDirectory(__DIR__.'/../Package/test-package2');
});

it('should initialize a new package project without assets', function () {
    Process::partialMock()->shouldReceive('concurrently')
        ->andReturn(0);

    $this->artisan('new')
        ->expectsQuestion('Author name', 'Test User')
        ->expectsQuestion('Author email', 'test@example.com')
        ->expectsQuestion('Package name', 'test-package')
        ->expectsQuestion('Vendor name', 'test-user')
        ->expectsConfirmation('Do you want to create a standalone filament package?', 'no')
        ->expectsConfirmation('Do you want have custom assets?', 'no')
        ->assertExitCode(0);
});

it('should throw an expection if project directory already exists', function () {
    Process::partialMock()->shouldReceive('concurrently')
        ->andReturn(0);

    $this->artisan('new')
        ->expectsQuestion('Author name', 'Test User')
        ->expectsQuestion('Author email', 'test@example.com')
        ->expectsQuestion('Package name', 'test-package')
        ->expectsQuestion('Vendor name', 'test-user')
        ->expectsConfirmation('Do you want to create a standalone filament package?', 'no')
        ->expectsConfirmation('Do you want have custom assets?', 'no')
        ->assertExitCode(0);
})->throws(ProjectAlreadyExistsException::class);

it('should initialize a new package project', function () {
    Process::partialMock()->shouldReceive('concurrently')
        ->andReturn(0);

    $this->artisan('new')
        ->expectsQuestion('Author name', 'Test User')
        ->expectsQuestion('Author email', 'test@example.com')
        ->expectsQuestion('Package name', 'test-package2')
        ->expectsQuestion('Vendor name', 'test-user')
        ->expectsConfirmation('Do you want to create a standalone filament package?', 'no')
        ->expectsConfirmation('Do you want have custom assets?', 'yes')
        ->expectsConfirmation('Do you need css with TailwindCSS setup?', 'yes')
        ->expectsQuestion('Do you add a custom css file name?', 'app')
        ->expectsConfirmation('Do you need js with AlpineJS setup?', 'yes')
        ->expectsQuestion('Do you add a custom js file name?', 'app')
        ->expectsConfirmation('Do you need blade templates/views?', 'yes')
        ->assertExitCode(0);
});
