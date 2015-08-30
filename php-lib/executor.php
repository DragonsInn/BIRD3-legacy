<?php

require_once "common.php";

// This could be considered the actual runner.
// It's STDOUT and STDERR become the request output.
// A process whose only purpose is to run and die.
// Hoooooowever. Due to Yii needing header stuff,
// we have to keep this in mind.
// So we re-include YiiApp to get our header-friendly code.

require_once("../php_modules/autoload.php");
require_once("YiiApp.php");
require_once("Log.php");
// Unserialize our stuff.
$args = hprose_unserialize($_SERVER["CONFIG"]);
$req = $args["req"];
$opt = $args["opt"];
foreach($args["env"] as $k=>$v) $_ENV[$k]=$v;
$config = $opt['config'];
$_ENV["config"] = $config;

// Build the request stuff.
// YiiApp.php created the arrays for us.
foreach($req["request"] as $key=>$val) {
    if(!is_array($GLOBALS[$key])) $GLOBALS[$key] = array();
    if(!is_array($val)) continue;
    $GLOBALS[$key]=array_merge($GLOBALS[$key], $val);
}
// Prepare to respond.
$req = new HttpRequest();
$res = new HttpResponse();

// Now the modified app.php
// remove the following line when in production mode
#defined('YII_DEBUG') or define('YII_DEBUG',true);

// If Yii::app()->end or exit is used, this should do something.
register_shutdown_function(function() use($res){
    $out = ob_get_contents();
    if($out!==false) {
        // This was a clean request, stuff is given.
        // On a rough request, we're fine with what is on STDOUT.
        ob_end_clean();
        echo hprose_serialize($res->end($out));
    }
});

function dump_cookie($start=false) {
    if(isset($_COOKIE["PHPSESSID"]))
        Log::info(($start?"Start":"Stop").": {$_SERVER['REQUEST_URI']} -> {$_COOKIE['PHPSESSID']}");
    else
        Log::info(($start?"Start":"Stop").": {$_SERVER['REQUEST_URI']} -> NONE");
}

// Resolve and run the request
ob_start();
$file = join(DIRECTORY_SEPARATOR, [$config["base"], $_SERVER["REQUEST_URI"]]);
if(!file_exists($file) || is_dir($file)) {
    // This is a request for Yii.
    $res->header("Content-type: text/html");
    $config=dirname(__FILE__).'/../app/config/main.php';
    $c=require_once($config);
    Yii::createWebApplication($c);
    set_error_handler("exception_error_handler");
    try{
        Yii::app()->run();
    } catch(Exception $e) {
        Log::error("Something bad happened.");
    }
} else {
    require_once($file);
}
