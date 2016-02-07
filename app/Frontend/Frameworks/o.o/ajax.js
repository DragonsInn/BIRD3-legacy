// We use the nanoajax library here.
var uxhr = require("uxhr");
var _ = require("microdash");
console.log(uxhr)
// Exporting the plugin
module.exports = {
    uxhr: uxhr,
    ajax: function(url, opt) {
        opt = opt || {
            complete: function(res, code) {
                console.log(code, res);
            }
        };
        opt.data = opt.data || {};
        return uxhr(url, opt.data, _.extend({
            headers: {
                // Make this a valid AJAX request.
                "X-Requested-With": "XMLHttpRequest"
            }
        }, opt));
    }
}
