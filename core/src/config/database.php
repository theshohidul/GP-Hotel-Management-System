<?php

return [
    "default" => "postgresql",
    "connections" => [
        'postgresql' => [
            'type'     => $_ENV['DB_TYPE'] ?? 'pgsql',
            'host'     => $_ENV['DB_HOST'] ?? '127.0.0.1',
            'port'     => $_ENV['DB_PORT'] ?? '5432',
            'dbname'   => $_ENV['DB_NAME'] ?? 'wom',
            'user'     => $_ENV['DB_USER'] ?? 'postgres',
            'password' => $_ENV['DB_PASSWORD'] ?? 'supersecretpassword'
        ],
    ]
];