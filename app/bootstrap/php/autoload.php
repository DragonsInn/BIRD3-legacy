<?php

define('LARAVEL_START', microtime(true));

require_once __DIR__.'/../../php_modules/autoload.php';

$compiledPath = __DIR__.'/cache/compiled.php';

if (file_exists($compiledPath)) {
    require_once $compiledPath;
}
