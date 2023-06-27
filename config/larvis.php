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
            'query' => '/api/query',
            'request' => '/api/request',
        ],
    ],
    'watchers' => [
        'requests' => [
            'enabled' => false,
        ],
        'queries' => [
            'enabled' => false,
        ],
        'exceptions' => [
            'enabled' => false,
        ],
    ],
    'reader' => [
        'model' => [
            'props' => [
                'connection',
                'table',
                'primaryKey',
                'keyType',
                'incrementing',
                'with',
                'withCount',
                'preventsLazyLoading',
                'perPage',
                'exists',
                'hidden',
                'attributes',
                'original',
                'changes',
                'casts',
                'dates',
                'dateFormat',
                'appends',
                'relations',
                'touches',
                'timestamps',
                'visible',
                'fillable',
                'guarded',
                'rememberTokenName',
                'accessToken',
            ],
        ],
        'collection' => [
            'props' => [
                'items',
                'escapeWhenCastingToString',
            ],
        ],
    ],
];
