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
AppServer::on("start", function($w){
    $pid = getmypid();
    #Log::info("BIRD3 worker@$pid is online!");
});
AppServer::on("stop", function($w){
    $pid = getmypid();
    Log::warn("BIRD3 worker@$pid is going down.");
});
AppServer::on("error", function($connection, $error_code, $error_msg){
    $msg = "AN ERROR OCCURED: $error_code : $error_msg";
    Log::error($msg);
    $connection->send($msg);
    $connection->end();
});

// Add the YiiApp.
require_once "YiiApp.php";
AppServer::on("start", function($w){
    $h = AppServer::hprose();
    $h->addClassMethods("YiiApp", null, "yii");
});

AppServer::on("error", function(){
    # Reload this worker
    YiiApp::stop();
});
