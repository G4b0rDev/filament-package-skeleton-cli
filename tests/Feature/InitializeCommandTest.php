<?php

use App\Exceptions\ProjectAlreadyExistsException;
use App\Facades\Config;
use Illuminate\Support\Facades\Process;

beforeEach(function () {
    $path = __DIR__.'/../Package';
    Config::set('path', $path);
    chdir($path);
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

    $path = __DIR__.'/../Package';
    Config::set('path', null);
    chdir($path);

    $this->artisan('new')
        ->expectsQuestion('Author name', 'Test User')
        ->expectsQuestion('Author email', 'test@example.com')
        ->expectsQuestion('Package name', 'test-package2')
        ->expectsQuestion('Vendor name', 'test-user')
        ->expectsConfirmation('Do you want to create a standalone filament package?', 'no')
        ->expectsConfirmation('Do you want have custom assets?', 'yes')
        ->expectsConfirmation('Do you need css with TailwindCSS setup?', 'yes')
        ->expectsQuestion('Do you add a custom css file name?', ' ')
        ->expectsConfirmation('Do you need js with AlpineJS setup?', 'yes')
        ->expectsQuestion('Do you add a custom js file name?', ' ')
        ->expectsConfirmation('Do you need blade templates/views?', 'yes')
        ->assertExitCode(0);
});
