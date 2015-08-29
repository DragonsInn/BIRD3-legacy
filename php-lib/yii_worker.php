<?php

require_once "common.php";

// Configure events
AppServer::on("start", function($w){
    if(function_exists("setproctitle")) setproctitle("BIRD3: Workerman Worker");
    $pid = getmypid();
    #Log::info("BIRD3 worker@$pid is online!");
});
AppServer::on("stop", function($w){
    $pid = getmypid();
    Log::notice("BIRD3 worker@$pid is going down.");
});
AppServer::on("error", function($connection, $error_code, $error_msg){
    $msg = "AN ERROR OCCURED: $error_code : $error_msg";
    Log::error($msg);
    $connection->send($msg);
    $connection->end();
});

AppServer::on("connect", function($w){
    $pid = getmypid();
    #Log::info("BIRD3 worker@$pid got connection.");
});
AppServer::on("close", function($w){
    $pid = getmypid();
    #Log::info("BIRD3 worker@$pid lost connection.");
});
AppServer::on("buffer_full", function($w){
    $pid = getmypid();
    Log::info("BIRD3 worker@$pid: Buffer full!");
});
AppServer::on("buffer_empty", function($w){
    $pid = getmypid();
    Log::info("BIRD3 worker@$pid: Buffer empty.");
});



// Add the YiiApp.
require_once "YiiApp.php";
AppServer::on("start", function($w){
    $h = AppServer::hprose();
    $h->setDebugEnabled(true);
    $h->addClassMethods("YiiApp", null, "yii");
});
