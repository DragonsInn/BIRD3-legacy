/*
    This file includes all components of Bootstrap that are used by BIRD3.

    The goal of this is to minimize the entire footprint of what is used in the long run.

    The pattern used here is the call-on-arrival way. Works neatly.
*/

(function(cb){
    $.ready(cb);
})(function(){
    // Load the equivalent for accessibility
    /*
    require("a11y.bs/js/functions");
    require("a11y.bs/js/modal");
    require("a11y.bs/js/dropdown");
    */
    window.Button = require("bootstrap.native/lib/button-native");
    window.Modal = require("bootstrap.native/lib/modal-native");
    window.Dropdown = require("bootstrap.native/lib/dropdown-native");
});
