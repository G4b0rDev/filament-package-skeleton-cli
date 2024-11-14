<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

readonly class Author
{
    public function __construct(
        public string $name,
        public string $email,
    ) {
        //
    }

    public static function from(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
        );
    }
}
