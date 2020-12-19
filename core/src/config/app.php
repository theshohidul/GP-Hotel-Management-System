<?php


use Monolog\Logger;

return [
    'displayErrorDetails' => true,
    'logErrorDetails' => true,
    'logErrors' => true,
    'logger' => [
        'name'  => 'APP',
        'path'  => base_path('storage/log/' .date('Y-m-d'). '.log'),
        'level' => Logger::DEBUG,
    ],
    'api_versions' => [
        'v1' => [
            'namespace' => '',
            'prefix' => '/api/v1',
            'file' => 'routes/v1.php',
            'middlewares' => [
                //\App\Middleware\Auth\AuthMiddleware::class
            ]
        ],
    ]
];