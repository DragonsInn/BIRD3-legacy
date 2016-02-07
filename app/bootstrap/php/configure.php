<?php

use Monolog\Handler\StreamHandler;
use Monolog\Handler\RedisHandler;
use BIRD3\Backend\Log;

// Add important interfaces into service container
// Kernels serve to console and client
return function($app) {
    Log::info("Configuring...");

    Log::info(BIRD3\Backend\Http\Kernel::class);
    $app->singleton(
        Illuminate\Contracts\Http\Kernel::class,
        BIRD3\Backend\Http\Kernel::class
    );

    Log::info(BIRD3\Backend\Console\Kernel::class);
    $app->singleton(
        Illuminate\Contracts\Console\Kernel::class,
        BIRD3\Backend\Console\Kernel::class
    );

    Log::info(BIRD3\Foundation\Exceptions\Handler::class);
    $app->singleton(
        Illuminate\Contracts\Debug\ExceptionHandler::class,
        BIRD3\Foundation\Exceptions\Handler::class
    );

    Log::info("Monolog");
    $app->configureMonologUsing(function($monolog) {
        $path = APP_ROOT."/log/BIRD3.laravel.log";
        \BIRD3\Backend\Log::info("Logs to: $path");
        $monolog->pushHandler(new StreamHandler($path));
    });
};
