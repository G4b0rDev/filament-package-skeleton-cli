<?php

declare(strict_types=1);

namespace App\Commands;

use App\Actions\HandleDependencyInstall;
use App\Actions\Initialize\DisplaySummary;
use App\Actions\Initialize\HandleAuthorInformation;
use App\Actions\Initialize\HandlePackageInformation;
use App\Actions\PublishProjectAction;
use App\Actions\PublishStubs;
use LaravelZero\Framework\Commands\Command;

use function Laravel\Prompts\clear;

class InitializeCommand extends Command
{
    protected $signature = 'new
                            {name? : The name of the package}
                            {--standalone : Create a standalone package}';

    protected $description = 'Create a new FilamentPHP package project';

    public function __construct(
        protected HandleAuthorInformation $handleAuthorInformation,
        protected HandlePackageInformation $handlePackageInformation,
        protected DisplaySummary $displaySummary,
        protected PublishProjectAction $publishProjectAction,
        protected PublishStubs $publishStubs,
        protected HandleDependencyInstall $handleDependencyInstall,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $author = ($this->handleAuthorInformation)();

        clear();

        $package = ($this->handlePackageInformation)($this->argument('name'), $this->option('standalone'));

        clear();

        ($this->displaySummary)($author, $package);

        ($this->publishProjectAction)($package->name);
        ($this->publishStubs)($package, $author);

        ($this->handleDependencyInstall)($package);

        return Command::SUCCESS;
    }
}
