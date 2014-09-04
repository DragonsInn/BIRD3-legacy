<?php
// change the following paths if necessary
$yii=dirname(__FILE__).'/php_modules/yii/framework/yii.php';
$config=dirname(__FILE__).'/config/yii.php';

// remove the following line when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);

require_once($yii);
Yii::createWebApplication($config)->run();
