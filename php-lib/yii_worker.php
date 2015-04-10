<?php

error_reporting(E_ALL);
ini_set('display_errors','1');

// Configure globals
$sg = [
    '_SERVER', '_GET', '_POST',
    '_FILES', '_COOKIE', '_REQUEST',
    '_SESSION'
];
foreach($sg as $g) {
    if(isset($GLOBl[$g]) && !is_array($GLOBALS[$g])) {
        $GLOBALS[$g]=array();
    }
}
$_REQUEST=array_merge($_GET, $_POST);
$_ENV=array_merge($_ENV, $_SERVER);

// Configure events
AppServer::on("start", function($ctx){
    $pid = getmypid();
    Log::info("BIRD3 worker@$pid is online!");
});
AppServer::on("stop", function($ctx){
    $pid = getmypid();
    Log::info("BIRD3 worker@$pid is going down.");
});

// Add the YiiApp.
require_once "YiiApp.php";
AppServer::on("start", function($ctx){
    $h = AppServer::hprose();
    $h->addClassMethods("YiiApp", null, "yii");
});
