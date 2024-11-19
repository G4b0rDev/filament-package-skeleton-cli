<?php

declare(strict_types=1);

namespace App\Actions\Initialize;

use App\DataTransferObjects\Package;
use App\Facades\Config;

use function Laravel\Prompts\form;

class HandlePackageInformation
{
    public function __construct(protected HandleCustomAssets $customAssetsForm)
    {
        //
    }

    public function __invoke(?string $name, bool $standalone = false): Package
    {
        $data = form()
            ->intro('Package information')
            ->text(
                label: 'Package name',
                placeholder: $name ?? '',
                default: $name ?? '',
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
                default: $standalone ?? false,
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
                step: fn () => ($this->customAssetsForm)(),
            )
            ->submit();

        return Package::from($data);
    }

    public function validateSlugValue(string $attribute, string $value): ?string
    {
        if (empty($value)) {
            return "The {$attribute} is required.";
        }

        if (! preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $value)) {
            return "The {$attribute} format is invalid.";
        }

        return null;
    }
}
