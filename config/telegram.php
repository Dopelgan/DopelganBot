<?php
return [
    'bots' => [
        'mybot' => [
            'token' => env('TELEGRAM_BOT_TOKEN'),
            'webhook' => [
                'url' => env('TELEGRAM_WEBHOOK_URL'),
            ],
        ],
    ],
];
