<?php

// Stupid time zone .... >.>
date_default_timezone_set("UTC");

// error handling?
error_reporting(E_ALL);
ini_set('display_errors','1');

// change the following paths if necessary
$yii=dirname(__FILE__).'/php_modules/yii/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following line when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);

require_once($yii);
$c=include_once($config);
Yii::createWebApplication($c)->run();
