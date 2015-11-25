<?php

// Create new app
require_once(__DIR__."/paths.php");
$configure = require_once(__DIR__."/configure.php");
$app = new BIRD3\Foundation\WebApplication(
    APP_ROOT,   # App base
    CONFIG_ROOT # Config
);

$configure($app);

return $app;
