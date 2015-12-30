// We use the nanoajax library here.
var nanoajax = require("nanoajax");
var _ = require("microdash");

// The base
function AJAX() {
    // Determine arguments
    var args = Array.prototype.slice.call(arguments);
    var o = {};
    if(_.isPlainObject(args[0])) {
        // f(Object) -> NanoAjax call
        o = args[0];
    } else if(_.isString(args[0])) {
        // f(String, Object)
        o.url = args[0];
        o = _.extend(o, args[1] || {});
    } else if(_.isString(args[0]) && _.isString(args[1])) {
        // f(String, String, Object)
        o.url = args[0];
        o.method = args[1].toUpperCase();
        o = _.extend(o, args[2] || {});
    }
    return nanoajax.ajax(o);
}

// Exporting the plugin
module.exports = {
    ajax: AJAX,
    nanoajax: nanoajax
}
