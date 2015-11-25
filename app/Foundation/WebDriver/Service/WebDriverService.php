<?php namespace BIRD3\Foundation\WebDriver\Service;

/*
    This is the entry script for the Workerman WebDrive server.

    It depends on:
        - hprose
        - Workerman
        - hprose-workerman

    It is configured using a JSON string stored in an environment variable.

    $_ENV["BIRD3_WEBDRIVER_CONF"] = [
        "composerFile" => String,
        "mainClass" => Some\Class,
        "args" => Array
    ];

    The "args" property is passed to the constructor.
    That means, you are litterally passing values across - be careful what you do!
*/

// Sanity checks
$envKey = "BIRD3_WEBDRIVER_CONF";
if(!isset($_SERVER[$envKey])) {
    die("Unable to configure WebDriver: Configuration not in environment ($envKey)");
} else {
    $config = json_decode($_SERVER[$envKey]);
}

if(!file_exists($config->composerFile)) {
    die("Can not find Composer autoloader: {$config->composerFile}.");
} else {
    require_once($config->composerFile);
}

if(!class_exists($config->mainClass)) {
    die("Class {$config->mainClass} was not found.");
}

$WebDriverContractInterface = \BIRD3\Foundation\WebDriver\Contracts\WebDriver::class;
if(array_key_exists(
    $WebDriverContractInterface,
    class_implements($config->mainClass)
)) {
    call_user_func([
        $config->mainClass,
        "InitializeAndRun"
    ], $config->args);
} else {
    die("{$config->mainClass} does not implement {$WebDriverContractInterface}.");
}

// Configure workerman, a little bit.
if(isset($config->stdoutFile)) {
    \Workerman\Worker::$stdoutFile = $config->stdoutFile;
}
if(isset($config->logFile)) {
    \Workerman\Worker::$logFile = $config->logFile;
}

// Run!
\Workerman\Worker::runAll();
