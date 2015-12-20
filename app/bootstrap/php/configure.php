<?php

use Monolog\Handler\StreamHandler;
use Monolog\Handler\RedisHandler;

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

    $app->configureMonologUsing(function(\Monolog\Logger $monolog){
        $monolog->pushHandler(new StreamHandler(APP_ROOT."/log/BIRD3.laravel.log"));
    });
};
