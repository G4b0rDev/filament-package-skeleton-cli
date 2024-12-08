<?php

use App\ConfigHandler;

beforeEach(function () {
    $this->config = app(ConfigHandler::class, ['configPath' => __DIR__.'/../Package/filament-package-skeleton/config.json']);
});

it('should create a config file if it does not exist', function () {
    expect(file_exists(__DIR__.'/../Package/filament-package-skeleton/config.json'))->toBeTrue();
});

it('can get a config value', function () {
    $this->config->set('path', '/home/username/projects');
    expect($this->config->get('path'))->toBe('/home/username/projects');
});

it('should return a default value when the config value does not exist', function () {
    $this->config->set('path', null);
    expect($this->config->get('path', 'default'))->toBe('default');
});

it('should throw an exception when trying to get a non-existent property', function () {
    $this->config->get('nonExistentProperty');
})->throws(InvalidArgumentException::class);

it('can set a config value', function () {
    $this->config->set('path', '/home/username/projects');
    $this->config->set('vendorName', 'test-author');

    expect($this->config->get('path'))->not()->toThrow(InvalidArgumentException::class);
});

it('should throw an exception when trying to set a non-existent property', function () {
    $this->config->set('nonExistentProperty', 'value');
})->throws(InvalidArgumentException::class);

it('can save a config value', function () {
    $this->config->set('path', '/home/username/projects');
    $this->config->save();

    $config = app(ConfigHandler::class, ['configPath' => __DIR__.'/../Package/filament-package-skeleton/config.json']);

    expect($config->get('path'))->toBe('/home/username/projects');
});
