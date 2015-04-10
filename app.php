<?php

// Stupid time zone .... >.>
date_default_timezone_set("UTC");

// error handling?
error_reporting(E_ALL);
ini_set('display_errors','1');

// change the following paths if necessary
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following line when in production mode
#defined('YII_DEBUG') or define('YII_DEBUG',true);

#require_once("php_modules/deps_check.php");
require_once("php_modules/autoload.php");

$c=include_once($config);
Yii::createWebApplication($c);

// I really want this, gdamnit
/*Yii::app()->onBeginRequest = function($e) {
    #return ob_start("ob_gzhandler");
};
Yii::app()->onEndRequest = function($e) {
};*/

Yii::app()->run();
