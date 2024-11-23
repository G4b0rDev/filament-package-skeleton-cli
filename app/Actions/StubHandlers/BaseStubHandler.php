<?php

declare(strict_types=1);

namespace App\Actions\StubHandlers;

use App\DataTransferObjects\Author;
use App\DataTransferObjects\Package;
use Illuminate\Support\Facades\File;

abstract class BaseStubHandler
{
    protected string $basePath;

    protected Package $package;

    protected Author $author;

    public function __construct(Package $package, Author $author)
    {
        $this->basePath = getcwd() . '/' . $package->name;
        $this->package = $package;
        $this->author = $author;
    }

    abstract public function __invoke(): void;

    public static function make(Package $package, Author $author): static
    {
        return new static($package, $author);
    }

    public function cleanup(string $path): void
    {
        (File::isFile($path))
            ? File::delete($path)
            : File::deleteDirectory($path);
    }
}
