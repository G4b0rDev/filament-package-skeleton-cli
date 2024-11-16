<?php

declare(strict_types=1);

namespace App\Actions\StubHandlers;

use Binafy\LaravelStub\Facades\LaravelStub;

class GithubActionsHandler extends BaseStubHandler
{
    public function __invoke(): void
    {
        $this->fundingStub();
        $this->issueTemplateStub();
    }

    protected function fundingStub(): void
    {
        $fundingStub = "{$this->basePath}/.github/FUNDING.yml.stub";

        LaravelStub::from($fundingStub)
            ->to($this->basePath)
            ->name('FUNDING')
            ->ext('yml')
            ->replace('VENDOR_NAME', $this->package->vendor)
            ->generate();

        $this->cleanUp($fundingStub);
    }

    protected function issueTemplateStub(): void
    {
        $issueTemplateStub = "{$this->basePath}/.github/ISSUE_TEMPLATE/config.yml.stub";

        LaravelStub::from($issueTemplateStub)
            ->to($this->basePath)
            ->name('FUNDING')
            ->ext('yml')
            ->replaces([
                'VENDOR_NAME' => $this->package->vendor,
                'PACKAGE_NAME' => $this->package->name,
            ])
            ->generate();

        $this->cleanUp($issueTemplateStub);
    }
}
