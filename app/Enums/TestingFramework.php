<?php

declare(strict_types=1);

namespace App\Enums;

enum TestingFramework: string
{
    case Pest = 'Pest';
    case PHPUnit = 'PHPUnit';
}
