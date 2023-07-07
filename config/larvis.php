<?php
/**
 *  This settings handles Larvis behaviour for local debug with Krater and production
 *  report with MoonGuard.
 */
return [
    /**
     *  MoonGuard settings
     */
    'moonguard' => [
        /**
         * The domain where MoonGuard is located.
         */
        'domain' => env('MOONGUARD_DOMAIN'),

        /**
         * MoonGuard api endpoints.
         */
        'api' => [
            'exceptions' => env('MOONGUARD_EXCEPTION_LOGGER_ENDPOINT', '/moonguard/api/exceptions'),
        ],

        /**
         * Moonguard site configuration.
         */
        'site' => [
            'api_token' => env('MOONGUARD_SITE_API_TOKEN'),
        ],
    ],

    /**
     * Krater settings
     */
    'krater' => [
        /**
         *  If enabled, Larvis can report data to Krater.
         */
        'enabled' => env('KRATER_DEBUG', false),

        /**
         *  The url where the Krater app is listening for requests.
         */
        'url' => env('KRATER_DEBUG_URL', 'http://localhost:58673'),

        /**
         *  Krater api endpoints.
         */
        'api' => [
            'messages' => '/api/messages',
            'exceptions' => '/api/exceptions',
            'queries' => '/api/queries',
            'requests' => '/api/requests',
        ],
    ],

    /**
     * Watchers settings
     *
     * Important: requests and queries are not compatible with MoonGuard.
     */
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

    /**
     * Readers settings
     *
     * This are the properties that are readed from Larvis when an object is processed.
     * You can modify the properties to read from:
     *
     * Illuminate\Database\Eloquent\Model
     * Illuminate\Support\Collection
     */
    'readers' => [
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
