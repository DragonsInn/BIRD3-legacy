<?php

describe("Submissions", function(){

    describe("General", function(){
        it("can automatically detect the submission type.");
        describe("Type recognition", function(){
            it("treats audio files as music.");
            it("treats image files as an artwork/photo.");
            it("treats text files as stories.");
        });
    });

    describe("Interaction", function(){
        it("can be favorited.");
        it("can be tagged.");
        it("can be commented on.");
        it("can be flagged.");
        describe("Comment", function(){
            it("can be created.");
            it("can be deleted by the owner.");
            it("can be edited by the owner.");
            it("can be deleted by users with the permission.");
            it("can be edited by users with the permission.");
        });
    });

    describe("Editing", function(){
        it("can have a visibility.");
        it("can have a rating.");
        it("can be set to community-only, so a guest does not see it.");
        it("can be set to private, so only creator sees it.");
    });

    describe("Sourcing", function(){
        it("reflects who MADE the content.");
        it("reflects the content in BOTH galleries.");
        describe("Auto-Sourcing", function(){
            it("rejects plain image URLs (and suggests Google Reverse Search)");
            describe("Third-party services: Turn link into source mention", function(){
                it("does FurAffinity");
                it("does SoFurry");
                it("does Facebook");
                it("does Twitter");
            });
        });
    });

});
