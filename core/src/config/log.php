<?php

return [
    "medium" => [
        "file" => [
        ],
        "cloudwatch" => [
        ]
    ],
    "enable" => true,

    "request" => [
        'enable' => true,
        'accept_payload' => true,
    ],

    "response" => [
        'enable' => true,
        'accept_data' => true,
    ],
    "url" => [
        "enable" => false,
        "urls" => [
            '/users/balance',
            '/settings',
            '/api/v1/abc',
        ],
    ],
    "msisdn" => [
        "enable" => true,
        "msisdns" => [
            "2834758934",
        ],
    ],

    "database" => [
        "enable" => true,
        "query_logging_enable" => true,
    ],
    "redis" => [
        "enable" => true,

    ],

    "platform" => [
        "enable" => true,
        "platforms" => [
            //"android",
            "ios",
            "web",
        ]
    ],

    "platform_version" => [
        "enable" => true,
        "platform_versions" =>[
            "android" =>[ "2.3"],
            "ios" => [],
        ]
    ]
];