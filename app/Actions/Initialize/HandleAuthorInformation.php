<?php

declare(strict_types=1);

namespace App\Actions\Initialize;

use App\DataTransferObjects\Author;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;

use function Laravel\Prompts\form;

class HandleAuthorInformation
{
    public function __invoke(): Author
    {
        $gitUsername = Str::squish(Process::run('git config user.name')->output());
        $gitEmail = Str::squish(Process::run('git config user.email')->output());

        return Author::from($this->form($gitUsername, $gitEmail));
    }

    // @codeCoverageIgnoreStart
    public function form(string $gitUsername, string $gitEmail): array
    {
        return form()
            ->intro('Package author information')
            ->text(
                label: 'Author name',
                placeholder: $gitUsername,
                required: true,
                default: $gitUsername,
                name: 'name',
            )
            ->text(
                label: 'Author email',
                placeholder: $gitEmail,
                required: true,
                default: $gitEmail,
                name: 'email',
            )
            ->submit();
    }
    // @codeCoverageIgnoreEnd
}
