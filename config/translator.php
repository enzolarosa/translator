<?php

return [
    'git' => [
        'autoPush' => env('TRANSLATOR_AUTO_PUSH', false),
    ],

    'supported_language' => [
        'en',
        'it',
    ],

    'driver' => env('TRANSLATOR_DRIVER', 'file'), //'database'

    'store' => [
        'file' => [],
    ],

    'horizon' => [
        'enabled' => env('TRANSLATOR_HORIZON', false),
        'queue' => env('TRANSLATOR_QUEUE', 'translator'),
        'tries' => env('TRANSLATOR_TRIES', 1),
        'timeout' => env('TRANSLATOR_TIMEOUT', 0),
        'balance' => env('TRANSLATOR_BALANCE', 'simple'),
        'minProcesses' => env('TRANSLATOR_MIN_PROCESSES', 1),
        'maxProcesses' => env('TRANSLATOR_MAX_PROCESSES', 1),
        'balanceCooldown' => env('TRANSLATOR_BALANCE_COOLDOWN', 1),
        'balanceMaxShift' => env('TRANSLATOR_BALANCE_MAX_SHIFT', 1),
    ],

    'aws' => [
        'key' => env('AWS_TRANSLATE_API'),
        'secret' => env('AWS_TRANSLATE_KEY'),
        'region' => env('AWS_TRANSLATE_REGION', 'eu-central-1'),
    ],
];
