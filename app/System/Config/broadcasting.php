<?php return [
    // Default broadcaster driver
    'default' => env('BROADCAST_DRIVER', 'redis'),
    'connections' => [
        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
        ],

        'log' => [
            'driver' => 'log'
        ],
    ],
];
