<?php

declare(strict_types=1);

namespace Tests;

use Laravel\Prompts\Prompt;
use LaravelZero\Framework\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        Prompt::fallbackWhen(true);
    }
}
