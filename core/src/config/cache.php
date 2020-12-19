<?php

return [
    'default' => env('CACHE_DRIVER', 'redis'),
    'prefix' => env('CACHE_PREFIX', 'hotel:'),
    'connections' => [
        'redis' => [
            'driver'  => 'redis',
            'servers' => [
                [
                    'server'      => env('REDIS_HOST', '127.0.0.1'),
                    'port'      => env('REDIS_PORT', '6379'),
                ]
            ],
            'password'  => env('REDIS_PASSWORD'),
        ]
    ],
    'ttl' => 60, // set as seconds
    'response' => [
        'ttl' => env('CACHE_RESPONSE_TTL', 60),
    ],
];
