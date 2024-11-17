<?php

declare(strict_types=1);

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string get(string $key, ?string $default = null)
 * @method static void set(string $key, ?string $value = null)
 * @method static void save()
 *
 * @see \App\ConfigHandler
 */
class Config extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \App\ConfigHandler::class;
    }
}
