<?php

return [
    'git' => [
        'autoPush' => env('TRANSLATOR_AUTO_PUSH', false),
        'message' => env('TRANSLATOR_COMMIT_MESSAGE', 'updated translation with `translator` package.'),
    ],

    'locale' => 'en',

    'supported_language' => [
        'en',
        'it',
    ],

    'driver' => env('TRANSLATOR_DRIVER', 'file'),//'database'

    'store' => [
        'database' => [
            'table' => env('TRANSLATOR_TABLE_NAME', 'translator'),
            'connection' => env('TRANSLATOR_DB_CONNECTION', 'mysql'),
        ],

        'file' => [],
    ],

    'horizon' => [
        'enabled'=>env('TRANSLATOR_HORIZON',false),
        'queue' => env('TRANSLATOR_QUEUE', 'translator'),
        'tries' => env('TRANSLATOR_TRIES', 1),
        'timeout' => env('TRANSLATOR_TIMEOUT', 0),
        'balance' => env('TRANSLATOR_BALANCE', 'simple'),
        'minProcesses' => env('TRANSLATOR_MIN_PROCESSES', 1),
        'maxProcesses' => env('TRANSLATOR_MAX_PROCESSES', 1),
        'balanceCooldown' => env('TRANSLATOR_BALANCE_COOLDOWN', 1),
        'balanceMaxShift' => env('TRANSLATOR_BALANCE_MAX_SHIFT', 1),
    ],
];
