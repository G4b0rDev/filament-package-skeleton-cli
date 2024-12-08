<?php

use App\DataTransferObjects\Author;

it('creates an Author DTO with a valid name and email', function () {
    $data = [
        'name' => fake()->name(),
        'email' => fake()->email(),
    ];

    $author = Author::from($data);

    expect($author)->toBeInstanceOf(Author::class);

    expect($author->name)->toBe($data['name']);
    expect($author->email)->toBe($data['email']);
});
