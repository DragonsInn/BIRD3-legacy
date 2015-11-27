var _ = require("microdash");
var htmlChain = require("html-chain");
var queryEngine = require("qwery");
var domReady = require("domready");

/*
    Methods left to implement:
    - class() : get/set class(es)
    - id() : get/set the id
    - attr() : get/set attribute value
    - html() : get/set html content
    - text() : get/set html content
    - css() : get/set css
    - height() / width() : height, width
*/

function DOM() {
    var inst = Object.create(DOM.prototype.__init);
    return inst.apply(inst, arguments);
}

DOM = _.extend({
    html: htmlChain,
    ready: domReady,
    queryEngine: queryEngine,
    dom: DOM
});
DOM.prototype = {
    __init: function() {
        var args = arguments;
        if(args[0] instanceof HTMLElement) {
            // Single element
            this.length = 1;
            this[0] = args[0];
        } else if(args[0] instanceof Array) {
            // Collection (array) of elements
            this.length = args[0].length;
            for(var i=0; i<args[0]; i++) {
                this[i] = args[0][i];
            }
        } else {
            // It might be a selector, so pass it to zest.
            var rt = queryEngine.apply(this, args);
            if(_.isArray(rt)) {
                this.length = rt.length;
                for(var i=0; i<rt.length; i++) {
                    this[i] = rt[i];
                }
            }
        }
        return this;
    },
    nodes: function() {
        var n = new Array(this.length);
        for(var i=0; i<this.length; i++) {
            n.push(n[i]);
        }
    },
    each: function(cb) {
        for(var i=0; i<this.length; i++) {
            cb.call(this, this[i]);
        }
    }
};

module.exports = DOM;
