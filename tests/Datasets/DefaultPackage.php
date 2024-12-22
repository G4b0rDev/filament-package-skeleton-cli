<?php

dataset('defaultPackage', function () {
    return [
        [
            'package' => [
                'package' => 'my-simple-plugin',
                'vendor' => 'test-user',
                'isStandalone' => false,
                'assets' => null,
            ],
            'author' => [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ],
        ],
    ];
});
