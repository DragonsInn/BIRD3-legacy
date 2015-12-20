<?php

use BIRD3\Support\GlobalConfig;

describe("Configuration", function(){

    it("should load the configuration", function(){
        GlobalConfig::load();
    });

    it("should have an array as config store", function(){
        $store = GlobalConfig::getInstance()->store;
        expect($store)->toBeA("array");
    });

});
