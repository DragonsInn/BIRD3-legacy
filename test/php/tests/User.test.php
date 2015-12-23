<?php

use BIRD3\Foundation\User\Entity as User;

describe("User", function(){

    describe("Account", function(){
        it("can create an account.");
        it("can log in.");
        it("can log out.");
        it("has default properties attached.");
        it("can delete the account");
    });

    describe("Relationships", function(){
        it("has settings.");
        it("has a profile.");
        it("has a role.");
        it("has permissions.");
        it("has private conversations.");
        it("has updates.");
    });

    describe("Private Messages", function(){
        it("can send a message to 1 user.");
        it("can send a message to many users.");
    });

    describe("Settings", function(){
        it("has a profile avatar.");
        xit("has default settings.", function(){
            // Iterate over Settings::$entries
            // and make sure the type matches the default property.
        });
    });

    describe("Permissions", function(){
        it("can have many permissions");
        it("can revoke a permission");
        it("can test for a permission");
    });

    describe("Banning", function(){
        it("bans users within their record.");
        it("bans permanently by IP.");
        it("bans permanently by cookie.");
        it("bans permanently by browser hash.");
        it("rejects registrations with known spammer EMail.");
        it("shows a reason when User hits the ban.");
        it("can be revoked by admins.");
        it("can be revoked by moderators with permission.");
    });

    describe("Suspension", function(){
        it("lasts only for a specified time.");
        it("deletes itself upon hit.");
    });

    describe("Banning and Suspension", function(){
        it("can convert between the two.");
    });

});
