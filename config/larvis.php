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
];
