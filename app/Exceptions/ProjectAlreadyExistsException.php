<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class ProjectAlreadyExistsException extends Exception
{
    public static function create(string $projectName): self
    {
        return new self("The project {$projectName} already exists.");
    }
}
