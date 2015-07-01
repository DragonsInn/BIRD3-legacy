var $ = require("cash");

// jQuery methods
$.ready = function(fn) {
    $(document).ready(fn);
}
$.fn.load = $.fn.ready;
$.load = $.ready;

$.fn.click = function(cb) {
    if(this[0] === null) return null;
    $(this).on("click", cb);
}

// Implement a tiny data store
$.data = function(elem, target) {
    return $.grab(elem).data($("body").data("cshid")+target);
}

$.fn.toggleClass = function(cl) {
    if(this.hasClass(cl)) {
        this.removeClass(cl);
    } else {
        this.addClass(cl);
    }
}

// Implement the way to get native Element objects
// Beware, its minimal.
$.grab = function(el) {
    var raw = $();
    if(typeof el == "object") {
        raw.length=1;
        raw[0]=el;
    } else if(typeof el == "array") {
        raw.length = el.length;
        raw[0] = el;
    } else {
        throw new TypeError("Expected argument 1 to be an object or array. Got "+typeof(el)+" instead.");
    }
    return raw;
}

// Export
module.exports = $;
global.$ = $;
global.jQuery = $;
