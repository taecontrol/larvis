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
    'debug' => [
        'enabled' => env('LARVIS_DEBUG_ENABLED', false),

        'url' => env('LARVIS_DEBUG_CLIENT_DOMAIN', 'http://localhost:55555'),

        'api' => [
            'message' => '/api/message',
            'exception' => '/api/exception',
        ],
    ],
    'watchers' => [
        Watchers\RequestWatcher::class => [
            'enabled' => env('LARVIS_REQUEST_WATCHER', true),
            'size_limit' => env('LARVIS_RESPONSE_SIZE_LIMIT', 64),
            'ignore_http_methods' => [],
            'ignore_status_codes' => [],
        ],
    ],
];
