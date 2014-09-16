<?php #print_r($_SERVER); die();
// change the following paths if necessary
$yii=dirname(__FILE__).'/php_modules/yii/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following line when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);

require_once($yii);
Yii::createWebApplication($config)->run();
