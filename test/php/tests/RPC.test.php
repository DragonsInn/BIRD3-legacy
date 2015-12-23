<?php

describe("Remote Call Protocoll", function(){
    it("can connect to NodeJS.");
    it("can call public APIs through hprose.");
    it("can call private APIs through hprose.");

    describe("Use case tests", function(){
        it("parses Markdown remotely.");
        it("runs JavaScript and returns the result.");
    });
});
