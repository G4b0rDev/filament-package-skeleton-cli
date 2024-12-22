<?php

use App\Actions\PublishProjectAction;
use App\Actions\PublishStubs;
use App\DataTransferObjects\Author;
use App\DataTransferObjects\Package;
use App\Facades\Config;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

afterAll(function () {
    Arr::map(File::directories(__DIR__.'/../Package'), fn (string $directory) => File::deleteDirectory($directory));
});

it('should generate the package service provider', function (array $package, array $author) {
    Config::set('path', __DIR__.'/../Package');

    $package = Package::from($package);
    $author = Author::from($author);

    app(PublishProjectAction::class)($package->name);
    app(PublishStubs::class)($package, $author);

    expect(file_exists(__DIR__.'/../Package/my-simple-plugin/src/MySimplePluginServiceProvider.php'))->toBeTrue();
})->with('defaultPackage');
