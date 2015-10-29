<?php

return [

    // The driver to use to log users in
    // FIXME: Implement BIRD3User driver
    'driver' => 'eloquent',

    // Model to use
    'model' => BIRD3\User::class,
    'table' => 'users',
    'password' => [
        'email' => 'emails.password',
        'table' => 'password_resets',
        'expire' => 60,
    ],
];
