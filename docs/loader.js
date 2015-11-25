/*
    This module returns the BIRD3 documentation, found in this folder.

    The docs are returned as an array with each key being an object.

    [
        {
            title: "",
            entries: [
                { title: "...", body: "..." }
            ]
        }
    ]
*/
module.exports = (function(){
    var docCtx = require.context('./', true, /.+\.md$/);
    var path = require("path");
    var docs = [];
    docCtx.keys().sort().forEach(function(file){
        var dir = path.dirname(file.substr(1));
        var md = {
            topicTitle: (dir == "/" ? "" : (function(){
                // From:    /Characters/Guides/basic.md
                // To:      Characters / Guides
                return dir.substr(1).split("/").join(" / ").trim();
            })()),
            data: docCtx(file)
        };
        var currentTopic = {title: null, entries: []};
        // Search for the topic. Assign it, if it exists.
        for(var i=0; i<docs.length; i++) {
            if(docs[i].title == md.topicTitle) {
                currentTopic = docs[i];
                break;
            }
        }
        // Is this an existing topic?
        var isNew = false;
        if(currentTopic.title == null) {
            currentTopic.title = md.topicTitle;
            isNew = true;
        }
        currentTopic.push(md.data);
        if(isNew) docs.push(currentTopic);
    });

    // All done. Here are the BIRD3 docs!
    return docs;
})();
