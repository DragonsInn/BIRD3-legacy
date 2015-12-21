<?php

use BIRD3\Support\GlobalConfig;

describe("Support", function(){

    describe("GlobalConfig", function(){

        it("should load the configuration. Throws on failure.", function(){
            GlobalConfig::load();
        });

        it("should have the config loaded as array", function(){
            $store = GlobalConfig::getInstance()->all();
            expect($store)->toBeA("array");
        });

    });

});
