<?php

use App\Actions\PublishProjectAction;
use App\Actions\PublishStubs;
use App\DataTransferObjects\Author;
use App\DataTransferObjects\Package;
use App\Facades\Config;

it('should generate the package service provider', function () {
    Config::set('path', __DIR__.'/../Package');

    $package = Package::from([
        'package' => 'my-simple-plugin',
        'vendor' => 'test-user',
        'isStandalone' => false,
        'assets' => null,
    ]);

    $author = Author::from([
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);

    app(PublishProjectAction::class)($package->name);
    app(PublishStubs::class)($package, $author);

    expect(file_exists(__DIR__.'/../Package/my-simple-plugin/src/MySimplePluginServiceProvider.php'))->toBeTrue();
});
