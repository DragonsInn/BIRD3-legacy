/*
    This file includes all components of Bootstrap that are used by BIRD3.

    The goal of this is to minimize the entire footprint of what is used in the long run.
*/

// JavaScript
require("bootstrap.js/alert");
require("bootstrap.js/button");
require("bootstrap.js/modal");
require("bootstrap.js/dropdown");

// Load the equivalent for accessibility
require("a11y.bs/js/functions");
require("a11y.bs/js/alert");
require("a11y.bs/js/modal");
require("a11y.bs/js/dropdown");

// Opt-in stuff
var optin = [
    "tooltip",
    "popover"
];
$.each(optin, function(i,v){
    if($('[data-toggle="'+v+'"]').length > 0) {
        require(["bootstrap.js/"+v], function(){
            $('[data-toggle="'+v+'"]')[v]();
        });
    }
});
