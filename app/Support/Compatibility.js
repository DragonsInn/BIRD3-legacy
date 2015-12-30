// This script only does and does not include stuff for working with
// older browsers, or mobile ones.
// Main worry is IE. People still use this crap! Dx

/*
    web-socket-js
        Fall back to flash based websockets.

    https://code.google.com/p/sessionstorage/
        Store local data, on all browsers.
        This might also be Storage.js. FIXME: Test compatibility.

    js-polyfills
        Common polyfills for various of stuff.

    Classlist.js
        Element.classList on all browsers.

    Need to shim/pollyfill:
        - Audio
        - Video
*/

import oo from "o.o";
import foyer from "foyer";

export default function Compatibility(callback) {
    foyer([
        function WebSocketSupport(cb) {
            if(oo("html").class() != "pure") {
                // This browser is an Internet Explorer.
                // ... oh no.
                var ver_str = oo("html").attr("class");
                var ver = parseInt(ver_str.replace("ie",""));
                if(ver < 10) {
                    require.ensure([
                        "web-socket-js/WebSocketMain.swf",
                        "web-socket-js/swfobject.js",
                        "web-socket-js/web_socket.js"
                    ], function(require){
                        window["WEB_SOCKET_SWF_LOCATION"] = require("web-socket-js/WebSocketMain.swf");
                        require("web-socket-js/swfobject.js");
                        require("web-socket-js/web_socket.js");
                        cb();
                    }, "websocket-polyfill");
                }
            } else {
                cb();
            }
        },
        function classList(cb) {
            var pseudo_el = document.createElement("div");
            var support_cl = typeof pseudo_el.classList;
            if(support_cl != "object") {
                require.ensure([
                    "classlist-polyfill"
                ], function(require){
                    require("classlist-polyfill");
                    cb();
                }, "classlist-polyfill");
            } else {
                cb();
            }
        },
        function HTMLPolyfill(cb) {
            require("js-polyfills/html");
        },
        function DOMPolyfill(cb) {
            // require("js-polyfills/dom");
            // use o.o to polyfill .querySelect and .querySelectAll.
            if(! document.querySelect) {
                document.querySelect = function(sel) {
                    var rt = oo.queryEngine(sel);
                    return (rt[0] || rt);
                }
            }
            if(! document.querySelectAll) {
                document.querySelectAll = function(sel) {
                    return oo.queryEngine(sel);
                }
            }
            cb();
        },
        function ArrayForEach(cb) {
            // Copied and slightly reformatted from:
            // https://github.com/petermichaux/polyfill/blob/249935b0bfc10db22c35e6f32b728ba016b28581/src/array.js#L200-L236
            if (!Array.prototype.forEach) {
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
            }
            cb();
        }
    ], callback);
}
