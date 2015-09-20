<?php

// Includes
require_once(__DIR__."/../php_modules/autoload.php");

// Implementation
class YiiApi {
    public function __construct() {
        // Let's fake a Yii app!
        $c = require_once(__DIR__."/../app/config/main.php");
        Yii::createWebApplication($c); // I am still surprised that this works. xD
    }

    public function obtainState($cookies) {
        $key = Yii::app()->user->getStateKeyPrefix();
        $sc = Yii::app()->securityManager;
        $res = ["prefix"=>$key, "yii"=>null];
        if(isset($cookies[$key]) && ($data=$sc->validateData($cookies[$key]))!=null) {
            $res["yii"] = unserialize($data);
        } else {
            $res["yii"] = new stdClass;
        }
        return $res;
    }
}

// Install
AppServer::on("start", function($w){
    $h = AppServer::hprose();
    $h->setDebugEnabled(true);
    $h->add(new YiiApi());
});
