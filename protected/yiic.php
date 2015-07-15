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

require_once(dirname(__FILE__).'/../php_modules/autoload.php');

$c=dirname(__FILE__).'/config/console.php';
$config = require_once($c);

require_once(dirname(__FILE__)."/../php_modules/yiisoft/yii/framework/yiic.php");
