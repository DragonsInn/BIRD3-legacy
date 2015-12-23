<?php

describe("Chat", function(){
    describe("Messages", function(){
        it("requires a userID.");
        it("relates to it's author.");
        it("can delete message.");
    });
    describe("Logs", function(){
        it("can access logs as regular user");
        it("shows private messages and rooms to moderators");
        it("shows private messages and rooms to users with permission");
    });

    it("can kick users (Suspend).");
    it("can ban users (Ban).");

    describe("Muting/Ignoring", function(){
        it("can ignore a user, by resolving the name to ID.");
        it("can ignore a user directly by ID.");
        it("can mute a user for a specified time.");
        it("can mute a user globally via a permitted user.");
        it("can mute a user globally via a moderator.");
    });

    describe("Files", function(){
        it("is capable of storing files (temporarily).");
        describe("Metadata", function(){
            it("picks up image dimensions.");
            it("picks up MP3 ID3 tags.");
            it("picks up word count in a document.");
            it("picks up filesize.");
        });
    });
});
