<?php namespace BIRD3\App\Entry\PhpServer;

use BIRD3\Backend\Log;
use App;

// Configure events
App::on("WorkerStart", function($w){
    if(function_exists("setproctitle")) setproctitle("BIRD3: Workerman Worker");
    $pid = getmypid();
    Log::info("BIRD3 worker@$pid is online!");
});
App::on("WorkerStop", function($w){
    $pid = getmypid();
    Log::notice("BIRD3 worker@$pid is going down.");
});
App::on("Error", function($connection, $error_code, $error_msg){
    $msg = "AN ERROR OCCURED: $error_code : $error_msg";
    Log::error($msg);
    $connection->send($msg);
    $connection->end();
});

App::on("Connect", function($w){
    $pid = getmypid();
    #Log::info("BIRD3 worker@$pid got connection.");
});
App::on("Close", function($w){
    $pid = getmypid();
    #Log::info("BIRD3 worker@$pid lost connection.");
});
App::on("BufferFull", function($w){
    $pid = getmypid();
    Log::info("BIRD3 worker@$pid: Buffer full!");
});
App::on("BufferEmpty", function($w){
    $pid = getmypid();
    Log::info("BIRD3 worker@$pid: Buffer empty.");
});
