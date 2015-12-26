var _ = require("microdash");
var htmlChain = require("html-chain");
var queryEngine = require("qwery");
var domReady = require("domready");
var domEasy = require("dom-easy");

/*
    Methods left to implement:
    - class() : get/set class(es)
    - id() : get/set the id
    - attr() : get/set attribute value
    - html() : get/set html content
    - text() : get/set html content
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
    dom: DOM,
    domEasy: domEasy,
});

DOM.prototype = Object.create(domEasy.prototype);
DOM.prototype = _.extend(DOM.prototype, {
    // Props
    __blinkDom: true,
    length: 0,
    // Methods
    __init: function(/*selector|collection, attrs[, ...children...]*/) {
        var args = arguments;
        if(
            typeof args[0] ==  "string"
            && (
                typeof args[1] == "undefined"
                || typeof args[1] == "string"
                || args[1] instanceof HTMLElement
            )
            && typeof args[2] == "undefined"
        ) {
            // DOM("foo>bar.baz") : Selector call
            var els = queryEngine(args[0], args[1]);
            domEasy.call(this, els);
            this.length = els.length;
            els.forEach(function(e, i){ this[i]=e }.bind(this));
        } else if(
            (typeof args[0] == "string" || typeof args[0] == "object")
            && (args[1] == null || typeof args[1] == "object")
        ) {
            // DOM("div", null|{...}, ...children) : JSX call

            // Is the first arg an element or string?
            if(typeof args[0] == "string") {
                var e = document.createElement(args[0]);
            } else {
                var e = args[0];
            }

            // Load methods
            domEasy.call(this, e);

            // Attach
            this.length = 1;
            this[0] = e;

            // Add attributes and values.
            for(var key in args[1]) {
                var val = args[1][key];
                if(key != "style") {
                    if(typeof this.el[key] == "undefined") {
                        var o = {};
                        o[key] = val;
                        this.attr(o);
                    } else {
                        this.el[key] = val;
                    }
                } else {
                    this.style(val);
                }
            }

            // Attach children. They are rest args.
            var argId = 2, child = args[2];
            while(typeof child != "undefined") {
                if(typeof child == "string") {
                    this.append(document.createTextNode(child));
                } else {
                    if("length" in child) {
                        if(child.length == 1) {
                            this.append(child[0]);
                        } else {
                            child.forEach(function(e){
                                this.append(e);
                            }.bind(this));
                        }
                    } else {
                        this.append(child);
                    }
                }
                child = args[++argId];
            }
        } else if(args[0] instanceof HTMLElement || _.isArray(args[0])) {
            // DOM(Element|ElementCollection)
            // Single or multiple element(s)
            domEasy.call(this, args[0]);
            if(_.isArray(args[0])) {
                this.length = args[0].length;
                for(var i=0; i<args[0].length; i++) {
                    this[i]=args[0][i];
                }
            } else {
                this.length = 1;
                this[0] = args[0];
            }
        }
        return this;
    },
    each: function(cb) {
        for(var i=0; i<this.length; i++) {
            cb(this[i], i);
        }
    },
    forEach: function(cb) { this.each.call(this, cb); }
});

module.exports = DOM;
