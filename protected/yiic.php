<?php

// This prevents Yii from its stupidly ridiculous exit codes.
$pid = pcntl_fork();
if($pid === -1) {
    die("Can not fork!");
} else if($pid) {
    // Parent. Wait for Yii, then exit.
    pcntl_wait($status);
    exit(0); # Good bye npm debug log. Bah. >.<
}

// change the following paths if necessary
$yiic=dirname(__FILE__).'/../php_modules/autoload.php';
require_once($yiic);

$config=dirname(__FILE__).'/config/console.php';
$c = require_once($config);

$cli = Yii::createConsoleApplication($c);
$cli->run();
