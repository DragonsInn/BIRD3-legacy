<?php

define('LARAVEL_START', microtime(true));

// Search root...
$path = __DIR__;
while(!file_exists("$path/composer.json")) {
    $path .= "/..";
}
require_once "$path/php_modules/autoload.php";

$compiledPath = __DIR__.'/cache/compiled.php';

if (file_exists($compiledPath)) {
    require_once $compiledPath;
}
