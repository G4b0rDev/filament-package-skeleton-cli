<?php

use App\ConfigHandler;
use App\Facades\Config;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;

beforeEach(function () {
    App::instance(
        ConfigHandler::class,
        new ConfigHandler(configPath: __DIR__.'/../Package/filament-package-skeleton/config.json')
    );
});

afterAll(function () {
    File::deleteDirectory(__DIR__.'/../Package/filament-package-skeleton');
});

it('should create a config file if it does not exist', function () {
    $configPath = __DIR__.'/../Package/filament-package-skeleton/config.json';

    expect(is_dir(dirname($configPath)))->toBeTrue();
    expect(file_exists($configPath))->toBeTrue();
});

it('can get a config value', function () {
    Config::set('path', '/home/username/projects');

    expect(Config::get('path'))->toBe('/home/username/projects');
});

it('should return a default value when the config value does not exist', function () {
    Config::set('path', null);
    expect(Config::get('path'))->toBe(null);
});

it('should throw an exception when trying to get a non-existent property', function () {
    Config::get('nonExistentProperty');
})->throws(InvalidArgumentException::class);

it('can set a config value', function () {
    Config::set('path', '/home/username/projects');
    Config::set('vendorName', 'test-author');

    expect(Config::get('path'))->not()->toThrow(InvalidArgumentException::class);
});

it('should throw an exception when trying to set a non-existent property', function () {
    Config::set('nonExistentProperty', 'value');
})->throws(InvalidArgumentException::class);

it('can save a config value', function () {
    Config::set('path', '/home/username/projects');
    Config::save();

    expect(Config::get('path'))->toBe('/home/username/projects');
});
