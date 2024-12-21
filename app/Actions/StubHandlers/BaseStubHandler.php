<?php

declare(strict_types=1);

namespace App\Actions\StubHandlers;

use App\DataTransferObjects\Author;
use App\DataTransferObjects\Package;
use Illuminate\Support\Facades\File;

abstract class BaseStubHandler
{
    public function __construct(
        protected Package $package,
        protected Author $author,
        protected ?string $basePath = null
    ) {
        //
    }

    abstract public function __invoke(): void;

    public static function make(Package $package, Author $author, string $basePath): static
    {
        return new static($package, $author, $basePath);
    }

    public function cleanup(string $path): void
    {
        (File::isFile($path))
            ? File::delete($path)
            : File::deleteDirectory($path);
    }
}
