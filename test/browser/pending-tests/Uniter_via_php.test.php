<?php

describe("Uniter: PHP",function(){
    it("can import JavaScript modules.");
    it("can import PHP modules.");
    it("can export values to JavaScript.");
    it("can access global functions and classes.");

    describe("Installing", function(){
        it("can install a module as class.");
        it("can install a module as global variable.");
        it("can install a module as global function.");
        it("can nstall a module into a namespace, as class.");
        it("can nstall a module into a namespace, as function.");
    });
});
