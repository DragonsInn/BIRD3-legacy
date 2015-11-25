<?php



$root = \BIRD3\Support\Resolver::root(__DIR__);
$app = "$root/app";
$cfgRoot = "$app/System/Config";

define("APP_ROOT", realpath($root));
define("BIRD3_APP", realpath($app));
define("CONFIG_ROOT", realpath($cfgRoot));
