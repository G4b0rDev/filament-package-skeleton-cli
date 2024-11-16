<?php

declare(strict_types=1);

namespace App\Actions\Initialize;

use App\DataTransferObjects\Asset;

use function Laravel\Prompts\form;

class HandleCustomAssets
{
    public function __invoke(): Asset
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

        return (! is_null($data['assets'])) ? Asset::from($data['assets']) : null;
    }
}
