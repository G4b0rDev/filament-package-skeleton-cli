<?php

use App\Actions\HandleDependencyInstall;
use App\DataTransferObjects\FilamentPlugin;
use App\DataTransferObjects\Package;
use Illuminate\Support\Facades\Process;

it('installs dependencies for the generated package', function () {
    $package = new Package(
        name: 'test-package',
        vendor: 'test-vendor',
        filamentPlugin: FilamentPlugin::from([
            'isStandalone' => false,
        ]),
        asset: null,
        withAssets: false,
    );

    Process::fake();

    (new HandleDependencyInstall)($package);
    Process::assertRan('composer install');
    Process::assertRan('npm install');
});
