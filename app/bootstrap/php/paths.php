<?php

// This logic will automatically figure out, where int he world we are.
function find_root($path = null) {
    # If path is not null, use path, else DIR
    $path = !is_null($path) ?: __DIR__;
    while(!file_exists("$path/composer.json")) {
        $path = "$path/..";
    }
    return realpath($path);
}

$root = find_root();
$app = "$root/app";
$cfgRoot = "$app/System/Config";

define("APP_ROOT", realpath($root));
define("BIRD3_APP", realpath($app));
define("CONFIG_ROOT", realpath($cfgRoot));
