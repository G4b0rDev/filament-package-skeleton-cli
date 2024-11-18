<?php

use App\Facades\Config;
use LaravelZero\Framework\Commands\Command;

it('should cancel the configuration update', function () {
    $this->artisan('config')
        ->expectsConfirmation('Do you want to update the configuration?', 'no')
        ->expectsOutputToContain('Aborted.')
        ->assertExitCode(Command::FAILURE);
});

it('should save the new configuration', function () {
    Config::spy();

    $this->artisan('config')
        ->expectsConfirmation('Do you want to update the configuration?', 'yes')
        ->expectsQuestion('Relative root path', '/tmp/projects')
        ->expectsQuestion('Vendor name', 'acme')
        ->expectsConfirmation('Do you want to update the configuration?', 'yes')
        ->expectsOutputToContain('Configuration updated successfully.')
        ->assertExitCode(Command::SUCCESS);

    Config::shouldHaveReceived('set')->with('path', '/tmp/projects')->once();
    Config::shouldHaveReceived('set')->with('vendorName', 'acme')->once();
    Config::shouldHaveReceived('save')->once();

    Config::shouldReceive('get')->with('path')->andReturn('/tmp/projects');
    Config::shouldReceive('get')->with('vendorName')->andReturn('acme');

    expect(Config::get('path'))->toBe('/tmp/projects');
    expect(Config::get('vendorName'))->toBe('acme');
});

it('should abort the configuration update before change accepted', function () {
    $this->artisan('config')
        ->expectsConfirmation('Do you want to update the configuration?', 'yes')
        ->expectsQuestion('Relative root path', '/tmp/projects')
        ->expectsQuestion('Vendor name', 'acme')
        ->expectsConfirmation('Do you want to update the configuration?', 'no')
        ->expectsOutputToContain('Aborted.')
        ->assertExitCode(Command::FAILURE);
});
