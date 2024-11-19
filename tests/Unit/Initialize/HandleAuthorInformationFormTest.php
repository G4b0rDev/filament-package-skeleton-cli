<?php

use App\Actions\Initialize\HandleAuthorInformation;
use App\DataTransferObjects\Author;
use Illuminate\Support\Facades\Process;

it('should test the author information form', function () {
    Process::fake([
        'git config user.name' => Process::result(['output' => 'Test User']),
        'git config user.email' => Process::result(['output' => 'test@example.com']),
    ]);

    $mock = Mockery::mock(HandleAuthorInformation::class)->makePartial()->shouldAllowMockingProtectedMethods();
    $mock->shouldReceive('form')
        ->with('Test User', 'test@example.com')
        ->andReturn([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

    app()->instance(HandleAuthorInformation::class, $mock);

    $author = app(HandleAuthorInformation::class)();

    expect($author)->toBeInstanceOf(Author::class);
    expect($author->name)->toBe('Test User');
    expect($author->email)->toBe('test@example.com');
});
