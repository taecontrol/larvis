<?php

return [
    'moonguard' => [
        'domain' => env('MOONGUARD_DOMAIN'),

        'exception_logger' => [
            'endpoint' => env('MOONGUARD_EXCEPTION_LOGGER_ENDPOINT', '/api/exceptions'),
        ],
    ],
    'site' => [
        'api_token' => env('MOONGUARD_SITE_API_TOKEN'),
    ],
    'krater' => [
        'url' => env('KRATER_DOMAIN', 'http://localhost:55555'),

        'api' => [
            'message' => '/api/message',
        ],
    ],
];
