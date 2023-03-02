<?php

/**
 * * Configuracion para usar JWT para auth
 *DOC: https://www.devcoons.com/lumen-configuration-of-jwt-authentication/
 */

return [
    'defaults' => [
        'guard' => 'api',
        'passwords' => 'usuarios',
    ],

    'guards' => [
        'api' => [
            'driver' => 'jwt',
            'provider' => 'usuarios',
        ],
    ],

    'providers' => [
        'usuarios' => [
            'driver' => 'eloquent',
            'model' => \App\Models\Usuario::class
        ]
    ]
];
