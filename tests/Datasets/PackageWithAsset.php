<?php

dataset('packageWithAsset', function () {
    return [
        [
            'package' => [
                'package' => 'my-simple-plugin',
                'vendor' => 'test-user',
                'isStandalone' => true,
            ],
            'author' => [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ],
            'asset' => [
                'withCss' => true,
                'customCss' => [
                    'cssName' => 'app',
                ],
                'withJs' => true,
                'customJs' => [
                    'jsName' => 'app',
                ],
                'withViews' => true,
            ],
        ],
    ];
});
