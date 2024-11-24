
<?php

use App\Actions\StubHandlers\GithubActionsHandler;
use App\DataTransferObjects\Asset;
use App\DataTransferObjects\Author;
use App\DataTransferObjects\FilamentPlugin;
use App\DataTransferObjects\Package;
use Binafy\LaravelStub\Providers\LaravelStubServiceProvider;
use Illuminate\Filesystem\FilesystemServiceProvider;
use Illuminate\Support\Facades\File;

beforeEach(function () {
    app()->register(LaravelStubServiceProvider::class);
    app()->register(FilesystemServiceProvider::class);

    File::spy();

    $this->basePath = getcwd() . '/TestPackage';

    $this->author = Author::from([
        'name' => 'Test Author',
        'email' => 'test@example.com',
    ]);

    $this->package = new Package(
        name: str()->studly('test-package'),
        vendor: 'test-vendor',
        filamentPlugin: FilamentPlugin::from([
            'isStandalone' => false,
            'pluginName' => 'Test',
        ]),
        asset: Asset::from([
            'withCss' => false,
            'withJs' => false,
        ]),
        withAssets: false,
    );
});

it('should publish the funding.yml', function () {
    $fundingPath = "{$this->basePath}/.github/FUNDING.yml";
    $fundingStub = "{$this->basePath}/.github/FUNDING.yml.stub";

    File::shouldReceive('delete')
        ->once()
        ->with($fundingStub)
        ->andReturnTrue();

    File::shouldReceive('exists')
        ->with($fundingPath)
        ->andReturnTrue();

    File::shouldReceive('exists')
        ->with($fundingStub)
        ->andReturnFalse();

    GithubActionsHandler::make($this->package, $this->author)();

    expect(File::exists($fundingPath))->toBeTrue();
    expect(File::exists($fundingStub))->toBeFalse();
});

it('should publish the issue template', function () {
    $issueTemplatePath = "{$this->basePath}/.github/ISSUE_TEMPLATE/config.yml";
    $issueTemplateStub = "{$this->basePath}/.github/ISSUE_TEMPLATE/config.yml.stub";

    File::shouldReceive('delete')
        ->with($issueTemplateStub)
        ->once()
        ->andReturnTrue();

    File::shouldReceive('exists')
        ->with($issueTemplatePath)
        ->once()
        ->andReturnTrue();

    File::shouldReceive('exists')
        ->with($issueTemplateStub)
        ->once()
        ->andReturnFalse();

    GithubActionsHandler::make($this->package, $this->author)();

    expect(File::exists($issueTemplatePath))->toBeTrue();
    expect(File::exists($issueTemplateStub))->toBeFalse();
});
