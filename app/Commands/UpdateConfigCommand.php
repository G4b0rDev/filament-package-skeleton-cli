<?php

declare(strict_types=1);

namespace App\Commands;

use App\Facades\Config;
use LaravelZero\Framework\Commands\Command;

use function Laravel\Prompts\clear;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\error;
use function Laravel\Prompts\info;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\table;
use function Laravel\Prompts\text;

class UpdateConfigCommand extends Command
{
    protected $signature = 'config';

    protected $description = 'Change the configuration';

    public function handle(): int
    {
        intro('Package global configuration');
        table(
            headers: ['Project Path', 'Vendor Name'],
            rows: [
                [Config::get('path'), Config::get('vendorName')],
            ],
        );

        $confirmUpdate = confirm(
            label: 'Do you want to update the configuration?',
            default: false
        );

        if (! $confirmUpdate) {
            error('Aborted.');

            return Command::SUCCESS;
        }

        clear();

        $path = text(
            label: 'Relative root path',
            placeholder: '$HOME/projects',
            default: config('app.filament.path'),
            hint: 'The path to the root of your projects',
        );

        $vendorName = text(
            label: 'Vendor name',
            placeholder: 'acme',
            default: config('app.filament.vendor_name'),
            hint: 'The vendor name of your projects',
        );

        clear();

        intro('Summary of changes');
        table(
            headers: ['Project Path', 'Vendor Name'],
            rows: [
                [$path, $vendorName],
            ],
        );

        $confirmUpdate = confirm(
            label: 'Do you want to update the configuration?',
            default: true
        );

        if (! $confirmUpdate) {
            error('Aborted.');

            return Command::SUCCESS;
        }

        Config::set('path', $path);
        Config::set('vendorName', $vendorName);
        Config::save();

        clear();

        info('Configuration updated successfully.');

        return Command::SUCCESS;
    }
}
