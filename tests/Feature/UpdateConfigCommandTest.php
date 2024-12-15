<?php

use LaravelZero\Framework\Commands\Command;

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
        ->expectsOutputToContain('Aborted.')
        ->assertExitCode(Command::FAILURE);
});
