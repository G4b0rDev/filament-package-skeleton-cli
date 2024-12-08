<?php

declare(strict_types=1);

namespace App\Commands;

use App\Actions\HandleDependencyInstall;
use App\Actions\PublishProjectAction;
use App\Actions\PublishStubs;
use App\DataTransferObjects\Asset;
use App\DataTransferObjects\Author;
use App\DataTransferObjects\Package;
use App\Facades\Config;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;
use LaravelZero\Framework\Commands\Command;

use function Laravel\Prompts\clear;
use function Laravel\Prompts\form;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\table;

class InitializeCommand extends Command
{
    protected $signature = 'new
                            {name? : The name of the package}
                            {--standalone : Create a standalone package}';

    protected $description = 'Create a new FilamentPHP package project';

    protected ?Author $author = null;

    protected ?Package $package = null;

    public function __construct(
        protected PublishProjectAction $publishProjectAction,
        protected PublishStubs $publishStubs,
        protected HandleDependencyInstall $handleDependencyInstall,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        // Author information
        $this->author = $this->authorForm();

        clear();

        // Package information
        $this->package = $this->packageForm();

        clear();

        // Display summary
        $this->displayPackageSummary();

        // Publish project
        ($this->publishProjectAction)($this->package->name);
        ($this->publishStubs)($this->package, $this->author);

        ($this->handleDependencyInstall)($this->package);

        return Command::SUCCESS;
    }

    public function validateSlugValue(string $attribute, string $value): ?string
    {
        if (! preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $value)) {
            return "The {$attribute} format is invalid.";
        }

        return null;
    }

    protected function authorForm(): Author
    {
        $gitUsername = Str::squish(Process::run('git config user.name')->output());
        $gitEmail = Str::squish(Process::run('git config user.email')->output());

        $data = form()
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

        return Author::from($data);
    }

    protected function packageForm(): Package
    {
        $data = form()
            ->intro('Package information')
            ->text(
                label: 'Package name',
                placeholder: $this->argument('name') ?? '',
                default: $this->argument('name') ?? '',
                required: true,
                name: 'package',
                validate: fn ($value) => $this->validateSlugValue('package name', $value),
            )
            ->text(
                label: 'Vendor name',
                placeholder: Config::get('vendorName'),
                default: Config::get('vendorName'),
                required: true,
                name: 'vendor',
                validate: fn ($value) => $this->validateSlugValue('vendor name', $value),
            )
            ->confirm(
                label: 'Do you want to create a standalone filament package?',
                hint: 'If you choose yes, it will setup a filament plugin with a custom panel',
                default: $this->option('standalone') ?? false,
                name: 'isStandalone',
            )
            ->addIf(
                condition: fn ($answers) => $answers['isStandalone'] === true,
                name: 'filament',
                step: fn () => form()
                    ->text(
                        label: 'Filament plugin name',
                        required: true,
                        name: 'pluginName',
                    )
                    ->submit(),
            )
            ->add(
                name: 'assets',
                step: fn () => $this->assetsForm(),
            )
            ->submit();

        return Package::from($data);
    }

    protected function assetsForm(): ?Asset
    {
        $data = form()
            ->intro('Custom assets setup')
            ->confirm(
                label: 'Do you want have custom assets?',
                default: true,
                name: 'custom_assets',
            )
            ->addIf(
                condition: fn ($answers) => $answers['custom_assets'] === true,
                name: 'assets',
                step: fn () => form()
                    ->confirm(
                        label: 'Do you need css with TailwindCSS setup?',
                        default: true,
                        name: 'withCss',
                    )
                    ->addIf(
                        condition: fn ($answers) => $answers['withCss'] === true,
                        name: 'customCss',
                        step: fn () => form()
                            ->text(
                                label: 'Do you add a custom css file name?',
                                hint: '(Optional) This will be the name of the file in the css directory',
                                name: 'cssName',
                            )
                            ->submit(),
                    )
                    ->confirm(
                        label: 'Do you need js with AlpineJS setup?',
                        default: true,
                        name: 'withJs',
                    )
                    ->addIf(
                        condition: fn ($answers) => $answers['withJs'] === true,
                        name: 'customJs',
                        step: fn () => form()
                            ->text(
                                label: 'Do you add a custom js file name?',
                                hint: '(Optional) This will be the name of the file in the js directory',
                                name: 'jsName',
                            )
                            ->submit(),
                    )
                    ->confirm(
                        label: 'Do you need blade templates/views?',
                        default: true,
                        name: 'withViews',
                    )
                    ->submit(),
            )
            ->submit();

        return (! is_null($data['assets']))
            ? Asset::from($data['assets'])
            : null;
    }

    protected function displayPackageSummary(): void
    {
        intro('Summary author');
        table(
            headers: ['Author', 'Email'],
            rows: [
                [$this->author->name, $this->author->email],
            ],
        );

        intro('Summary package');
        $isStandalone = $this->package->filamentPlugin->isStandalone ? '✅' : '❌';
        $hasAssets = $this->package->withAssets ? '✅' : '❌';
        $hasViews = ($this->package->withAssets && $this->package->asset->withViews) ? '✅' : '❌';

        table(
            headers: ['Package', 'Vendor', 'Standalone', 'Custom Assets', 'Views'],
            rows: [
                [$this->package->name, $this->package->vendor, $isStandalone, $hasAssets, $hasViews],
            ]
        );

        spin(
            message: 'let me think... 🤔',
            callback: fn () => sleep(1),
        );
    }
}
