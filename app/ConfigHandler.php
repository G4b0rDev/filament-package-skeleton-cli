<?php

declare(strict_types=1);

namespace App;

use App\DataTransferObjects\Config;
use InvalidArgumentException;

class ConfigHandler
{
    protected string $configPath;

    protected Config $config;

    public function __construct()
    {
        $this->configPath = getenv('HOME') . '/.config/filament-package-skeleton/config.json';

        if (! file_exists(dirname($this->configPath))) {
            mkdir(dirname($this->configPath), 0755, true);
            touch($this->configPath);
        }

        $this->config = Config::from(json_decode(file_get_contents($this->configPath), true) ?? []);
    }

    public function get(string $key, ?string $default = null): ?string
    {
        if (! property_exists($this->config, $key)) {
            throw new InvalidArgumentException("Property {$key} does not exist in Config.");
        }

        return $this->config->{$key} ?? $default;
    }

    public function set(string $key, ?string $value = null): void
    {
        if (! property_exists($this->config, $key)) {
            throw new InvalidArgumentException("Property {$key} does not exist in Config.");
        }

        $this->config->{$key} = $value;
    }

    public function save(): void
    {
        file_put_contents($this->configPath, $this->config->toJson());
    }
}
