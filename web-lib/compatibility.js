// This script only does and does not include stuff for working with
// older browsers, or mobile ones.
// Main worry is IE. People still use this crap! Dx

/*
    web-socket-js
        Fall back to flash based websockets.

    https://code.google.com/p/sessionstorage/
        Store local data, on all browsers.
        This might also be Storage.js. FIXME: Test compatibility.

    jjs-polyfills
        Common polyfills for various of stuff.

    Classlist.js
        Element.classList on all browsers.

    Need to shim/pollyfill:
        - Audio
        - Video
*/

if($("html").class() != "pure") {
    // This browser is an Internet Explorer.
    // ... oh no.
    var ver_str = $("html").class();
    var ver = parseInt(ver_str.replace("ie",""));
    if(ver < 10) {
        // Include WebSockets.
    }
}

var pseudo_el = document.createElement("div");
var support_cl = typeof pseudo.el.classList;
if(support_cl != "object") {
    // Pull in classlist.js
}

// Minimal fills
require("js-polyfills/dom");
require("js-polyfills/html");
