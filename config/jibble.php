<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Jibble API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Jibble API integration
    |
    */

    'access_token' => env('JIBBLE_ACCESS_TOKEN'),
    'organization_id' => env('JIBBLE_ORGANIZATION_ID'),
    'enabled' => env('JIBBLE_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | Sync Settings
    |--------------------------------------------------------------------------
    */

    'sync' => [
        'employees' => [
            'enabled' => env('JIBBLE_SYNC_EMPLOYEES', true),
            'schedule' => 'daily', // daily, weekly, hourly
        ],
        'time_entries' => [
            'enabled' => env('JIBBLE_SYNC_TIME_ENTRIES', true),
            'schedule' => 'hourly',
            'lookback_days' => 7, // Sync last 7 days of time entries
        ],
        'time_off' => [
            'enabled' => env('JIBBLE_SYNC_TIME_OFF', true),
            'schedule' => 'daily',
        ],
    ],
];
