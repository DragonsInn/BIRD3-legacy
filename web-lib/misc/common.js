// jQuery
var $ = require("./jquery");

// Bootstrap.Native
require("./bootstrapper.js");
// Theme
require("dragonsinn/css/bs-extra.css");
require("dragonsinn/css/main.scss");
//require("dragonsinn/Birdcons/Birdcons.font.js");
// Syntax highlighting
require("highlight.js/styles/hybrid.css");

// BIRD3 Markdown editor
$.ready(function(){
    if($("body").find('div[data-b3me]').length > 0) {
        require(["BIRD3/js/editor"], function(editor){
            // Easy.
            editor();
        });
    }
});
