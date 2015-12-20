/**
 * @file This file takes care of checking compatibility with browsers.
 *
 * It implements:
 * 		- Polyfilling the browser.
 * 		- Setting custom global variables.
 * 		- Automatically loading async chunks.
 *
 * The chunks in this file are intentionally not named.
 *
 * Modules:
 *     web-socket-js
 *         Fall back to flash based websockets.
 *     https://code.google.com/p/sessionstorage/
 *         Store local data, on all browsers.
 *         This might also be Storage.js. FIXME: Test compatibility.
 *     js-polyfills
 *         Common polyfills for various of stuff.
 *     Classlist.js
 *         Element.classList on all browsers.
 *     Need to shim/pollyfill:
 *         - Audio
 *         - Video
 */

// Minimal fills
// require("js-polyfills/dom");
// require("js-polyfills/html");

module.exports = function Compatibility(callback) {
    require("foyer")([
        function WebSockets(done) {
            // Get first class name in HTML element.
            // <html class="pure"> == "pure"
            var ver_str = document.className.split(" ")[0];
            var ver = parseInt(ver_str.replace("ie",""));
            if(ver < 10) {
                window["WEB_SOCKET_SWF_LOCATION"] = require("web-socket-js/WebSocketMain.swf");
                require([
                    "web-socket-js/swfobject.js".
                    "web-socket-js/web_socket.js"
                ], function(){
                    done();
                });
            } else {
                // Nothing to worry about.
                done();
            }
        },
        function ClassList(done) {
            var pseudo_el = document.createElement("div");
            var support_cl = typeof pseudo_el.classList;
            if(support_cl != "object") {
                require(["classlist-polyfill"], function(){
                    done();
                });
            } else {
                done();
            }
        },
        function ArrayForEach(done) {
            // Copied and slightly reformatted from:
            // https://github.com/petermichaux/polyfill/blob/249935b0bfc10db22c35e6f32b728ba016b28581/src/array.js#L200-L236
            if (Array.prototype.forEach) {
                done();
            } else {
                Array.prototype.forEach = function(callbackfn /*, thisArg */) {
                    // step 1
                    if (this == null) {
                        throw new TypeError("can't convert " + this + " to object");
                    }
                    var O = Object(this);

                    // steps 2 & 3
                    var len = O.length >>> 0;

                    // step 4
                    if (typeof callbackfn != "function") {
                        throw new TypeError(callbackfn + " is not a function");
                    }

                    // step 5
                    var T = arguments[1];

                    // step 6
                    var k = 0;

                    // step 7
                    while (k < len) {
                        if (k in O) {
                            callbackfn.call(T, O[k], k, O);
                        }
                        k++;
                    }

                    // step 8
                    // return undefined;
                };
                done();
            }
        }
    ], callback);
}
