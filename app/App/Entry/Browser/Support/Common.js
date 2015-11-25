// jQuery
var $ = require("./jQueryize");

// Bootstrap.Native
require("./BootstrapNative.js");
// Theme
require("BIRD3/Frontend/Design/Styles/bs-extra.css");
require("BIRD3/Frontend/Design/Styles/main.scss");
//require("dragonsinn/Birdcons/Birdcons.font.js");
// Syntax highlighting
require("highlight.js/styles/hybrid.css");

// BIRD3 Markdown editor
$.ready(function(){
    if($("body").find('div[data-b3me]').length > 0) {
        require(["Editor/MarkdownEditor"], function(editor){
            // Easy.
            editor();
        });
    }
});
