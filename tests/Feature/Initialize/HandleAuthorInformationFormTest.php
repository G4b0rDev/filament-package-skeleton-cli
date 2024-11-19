<?php

use App\Actions\Initialize\HandleAuthorInformation;
use App\DataTransferObjects\Author;
use Illuminate\Support\Facades\Process;
use Laravel\Prompts\Key;
use Laravel\Prompts\Prompt;

it('should test the author information form', function () {
    Process::fake([
        'git config user.name' => Process::result(['output' => 'Test User']),
        'git config user.email' => Process::result(['output' => 'test@example.com']),
    ]);

    Prompt::fake([
        Key::ENTER,
        Key::ENTER,
    ]);

    app(HandleAuthorInformation::class);

    $author = app(HandleAuthorInformation::class)();

    expect($author)->toBeInstanceOf(Author::class);
    expect($author->name)->toBe('Test User');
    expect($author->email)->toBe('test@example.com');
});
