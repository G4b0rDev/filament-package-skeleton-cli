<?php

use App\ConfigHandler;
use App\Facades\Config;
use Illuminate\Support\Facades\App;
use LaravelZero\Framework\Commands\Command;

beforeEach(function () {
    App::instance(
        ConfigHandler::class,
        new ConfigHandler(configPath: __DIR__.'/../Package/filament-package-skeleton/config.json')
    );
});

it('should cancel the configuration update', function () {
    $this->artisan('config')
        ->expectsConfirmation('Do you want to update the configuration?', 'no')
        ->expectsOutputToContain('Aborted.')
        ->assertExitCode(Command::FAILURE);
});

it('should abort the configuration update before change accepted', function () {
    $this->artisan('config')
        ->expectsConfirmation('Do you want to update the configuration?', 'yes')
        ->expectsQuestion('Relative root path', '/tmp/projects')
        ->expectsQuestion('Vendor name', 'acme')
        ->expectsConfirmation('Do you want to update the configuration?', 'no')
        ->assertExitCode(Command::FAILURE);
});

it('should update the configuration', function () {
    $this->artisan('config')
        ->expectsConfirmation('Do you want to update the configuration?', 'yes')
        ->expectsQuestion('Relative root path', '/tmp/projects')
        ->expectsQuestion('Vendor name', 'acme')
        ->expectsConfirmation('Do you want to update the configuration?', 'yes')
        ->assertExitCode(Command::SUCCESS);

    expect(Config::get('path'))->toBe('/tmp/projects');
    expect(Config::get('vendorName'))->toBe('acme');
});
