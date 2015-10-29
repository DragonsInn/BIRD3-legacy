<?php

// Add important interfaces into service container
// Kernels serve to console and client
return function($app) {
    $app->singleton(
        Illuminate\Contracts\Http\Kernel::class,
        BIRD3\Backend\Http\Kernel::class
    );

    $app->singleton(
        Illuminate\Contracts\Console\Kernel::class,
        BIRD3\Backend\Console\Kernel::class
    );

    $app->singleton(
        Illuminate\Contracts\Debug\ExceptionHandler::class,
        BIRD3\Foundation\Exceptions\Handler::class
    );

    // Surge the global BIRD3 config in
    \BIRD3\Support\GlobalConfig::load(home_path("config/BIRD3.ini"));
};
