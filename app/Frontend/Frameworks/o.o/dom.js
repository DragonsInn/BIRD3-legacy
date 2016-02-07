var _ = require("microdash");
var queryEngine = require("qwery");
var domReady = require("domready");
var domEasy = require("dom-easy");
var cssValue = require("css-value");

/**
 * Construct a new o.o DOM object.
 * @return DOM
 */
function DOM() {
    var inst = Object.create(DOM.prototype);
    return DOM.prototype.__init.apply(inst, arguments);
}

/**
 * Convert string to snake-case
 * @param  {string} str String to convert
 * @return {String} Snake-case'd string.
 */
function toSnake(str) {
    return str.replace(/[A-Z]/g, function(L){
        return "-"+L.toLowerCase();
    })
}

/**
 * Public properties of DOM.
 */
DOM = _.extend(DOM, {
    ready: domReady,
    queryEngine: queryEngine,
    dom: DOM,
    domEasy: domEasy,
});

module.exports = DOM;
DOM.prototype = Object.create(domEasy.prototype);
DOM.prototype = _.extend(DOM.prototype, domEasy.prototype, {
    // # Props
    /**
     * Used to identify that this is an o.o Dom element.
     * @type {Boolean}
     */
    __blinkDom: true,

    /**
     * Pseudo length
     * @type {Number}
     */
    length: 0,

    // # Methods
    /**
     * Initialize the new DOM object.
     *
     * Possible usages:
     *
     * DOM Ready
     * @param Function                  Adds the given function to domReady.
     *
     * JSX (Use with Babel and the @jsx pragma!)
     * @param String/HTMLElement        Root element for this creation.
     * @param Null|Object               Attributes for the node.
     * @param ...children               A continued list of child elements. Optional.
     *
     * Query selection
     * @param String                    Query selector
     * @param String|HTMLElement        Context - either selector or node. Optional.
     *
     * Load single or multiple node(s)
     * @param HTMLElement|Collection    Load these nodes using o.o DOM.
     *
     * @return {o.o DOM}                Instance of o.o DOM. Except when using DomReady, then it's NULL.
     */
    __init: function(/*selector|collection, attrs[, ...children...]*/) {
        var args = arguments;
        if(_.isFunction(args[0])) {
            // DOM(function(){ ... })
            domReady(args[0]);
            return null;
        } else if(
            typeof args[0] ==  "string"
            && (
                typeof args[1] == "undefined"
                || typeof args[1] == "string"
                || args[1] instanceof HTMLElement
            )
            && typeof args[2] == "undefined"
        ) {
            // DOM("foo>bar.baz") : Selector call
            // console.log("Selector call:",args[0]);
            var els = queryEngine(args[0], args[1]);
            domEasy.call(this, els);
            this.length = els.length;
            els.forEach(function(e, i){ this[i]=e }.bind(this));
        } else if(
            (_.isString(args[0]) || _.isPlainObject(args[0]))
            && (args[1] == null || _.isPlainObject(args[1]))
        ) {
            // DOM("div", null|{...}, ...children) : JSX call
            // console.log("JSX call");

            // Is the first arg an element or string?
            if(_.isString(args[0])) {
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
                if(key == "style") {
                    this.css(val);
                } else if(key == "data") {
                    this.el.dataset = _.extend(this.el.dataset, val);
                } else {
                    if(typeof this[0][key] == "undefined") {
                        var o = {};
                        o[key] = val;
                        this.attr(o);
                    } else {
                        this[0][key] = val;
                    }
                }
            }

            // Attach children. They are rest args.
            for(var argId=2; argId<arguments.length; argId++) {
                var child = arguments[argId];
                if(_.isString(child)) {
                    this.appendChild(document.createTextNode(child));
                } else if(child == null || typeof child == "undefined") {
                    // null/undefined means, skip.
                    continue;
                } else {
                    if("length" in child) {
                        if(child.length == 1) {
                            this.appendChild(child);
                        } else {
                            child.forEach(function(e){
                                this.appendChild(e);
                            }.bind(this));
                        }
                    } else {
                        this.appendChild(child);
                    }
                }
            }
        } else if(args[0] instanceof HTMLElement || _.isArray(args[0])) {
            // DOM(Element|ElementCollection)
            // Single or multiple element(s)
            domEasy.call(this, args[0]);
            if(args[0] instanceof Array) {
                this.length = args[0].length;
                for(var i=0; i<args[0].length; i++) {
                    this[i]=args[0][i];
                }
            } else {
                this.length = 1;
                this[0] = args[0];
            }
        }

        // Put away unsafe methods.
        this._attr = this.attr;

        return this;
    },

    /**
     * Iterate over each captured element
     * @param {Function} cb Callback in the form of: cb(node, id, DOM(node))
     */
    each: function(cb) {
        for(var i=0; i<this.length; i++) {
            cb(this[i], i, DOM(this[i]));
        }
    },

    /**
     * Mapping to .each()
     */
    forEach: function(cb) { this.each.call(this, cb); },

    /**
     * Grab all the nodes.
     * @return Array
     */
    nodes: function() {
        var nodes = [];
        for(var i=0; i<this.length; i++) {
            nodes.push(this[i]);
        }
        return nodes;
    },

    appendChild: function(blinkOrNode){
        if(typeof blinkOrNode.__blinkDom != "undefined") {
            // o.o Dom
            blinkOrNode.each(function(node){
                this[0].appendChild(node);
            }.bind(this));
        } else {
            this[0].appendChild(blinkOrNode);
        }
    },

    // Append us to this object.
    appendTo: function(selector) {
        this.each(function(node){
            DOM(selector).appendChild(node);
        });
    },

    /**
     * Get or set a data- attribute.
     * @param {String} key Key to get or set.
     * @param {String} value Value to set. Setting is only performed, if value is given.
     * @return {String} The data- property.
     */
    data: function(key, value) {
        if(typeof value == "undefined") {
            return this[0].dataset[key];
        } else if(_.isPlainObject(key)) {
            _.extend(this[0].dataset, key);
        } else {
            this[0].dataset[key] = value;
        }
    },

    /**
     * Get or set a CSS property.
     * @param {String} key Key to get or set.
     * @param {Mixed} value Value to set. Setting is only performed, if value is given.
     * @return {Mixed} Value of the CSS rule.
     */
    css: function(key, value) {
        if(_.isPlainObject(key)) {
            for(var rule in key) {
                var data = key[rule];
                for(var i=0; i<this.length; i++) {
                    this[i].style[toSnake(rule)] = data;
                }
            }
        } else if(typeof value == "undefined") {
            if(this.length <= 1) {
                // Single return
                var css = window.getComputedStyle(this[0]);
                var val = css.getPropertyValue(key);
                return cssValue(val)[0].value;
            } else {
                var vals = [];
                this.each(function(e){
                    var css = window.getComputedStyle(e);
                    return cssValue(css.getPropertyValue(key))[0].value;
                });
                return vals;
            }
        } else {
            if(this.length < 1) {
                // Single return
                this[0].style[toSnake(key)] = value;
            } else {
                this.each(function(e){
                    e.style[toSnake(key)] = value;
                });
            }
        }
    },

    /**
     * Get height of element.
     * @return {Number} Height in picels (px).
     */
    height: function() {
        return this[0].offsetHeight;
    },

    /**
     * Get or set the innerText property of the FIRST element.
     * @param {String} txt If given, this becomes the new text.
     * @return {String} The containing string. Undefined when setting.
     */
    text: function(txt) {
        if(_.isString(txt)) {
            this[0].innerText = txt;
        } else {
            return this[0].innerText;
        }
    },

    /**
     * Get or set the HTML of the FIRST element.
     * @param {String} h If given, set the HTML to this.
     * @return {String} String of the node's HTML. Undefined when setting.
     */
    html: function(h) {
        if(typeof h != "undefined") {
            this[0].innerHTML = h;
        } else {
            return this[0].innerHTML;
        }
    },

    val: function(){
        return this[0].value;
    },

    /**
     * Get or set an attribute.
     * @param {String} k Attribute name
     * @param {String} v Attribute value. If given, perform set.
     * @return {String} Attribute value.
     *
     * If you specify an Object instead:
     * @param {Object} k Set this object as the new properties.
     * @return Nothing.
     */
    attr: function(k, v) {
        if(_.isPlainObject(k)) {
            for(var prop in k) {
                this[0].setAttribute(prop, k[prop]);
            }
        } else {
            if(_.isString(k) && typeof v == "undefined") {
                return this[0].attributes[k];
            } else if(_.isString(k) && _.isString(v)) {
                this[0].attributes[k]=v;
            }
        }
    },

    removeAttr: function(name){
        this[0].removeAttribute(name);
        return this;
    },

    /**
     * Get or set the FIRST element's ID.
     * @param {String} v ID to set. Setting is only performed if this is given.
     * @return {String} The ID.
     */
    id: function(v) {
        if(_.isString(v)) {
            this[0].id=v;
        } else {
            return this[0].id;
        }
    },

    class: function(){
        return this[0].className;
    },

    // Get the index in a className string.
    getClassIndex: function(c) {
        return this[0].className.split(" ").indexOf(c);
    },

    // Check if element0 has a class.
    hasClass: function(c) {
        return this.getClassIndex(c) > -1;
    },

    toggleClass: function(c) {
        var classIndex = this.getClassIndex(c);
        if(classIndex > -1) {
            // Disable class
            this.removeClass(c);
        } else {
            this.addClass(c);
        }
    },

    addClass: function(c){
        var classNames = this[0].className.split(" ");
        classNames.push(c);
        this[0].className = classNames.join(" ");
        return this;
    },

    removeClass: function(c){
        var classNames = this[0].className.split(" ");
        classNames = classNames.filter(function(className){
            return className != c;
        });
        this[0].className = classNames.join(" ");
        return this;
    },

    click: function(cb) {
        if(_.isFunction(cb)) {
            this.each(function(node){
                if(node.addEventListener) {
                    node.addEventListener("click", cb);
                } else {
                    node.attachEvent("click", cb);
                }
            });
        } else {
            this.each(function(node){
                node.click();
            })
        }
    },

    find: function(sel) {
        var context = this.nodes();
        var query = queryEngine(sel, context);
        var newDom = DOM(query);
        return newDom;
    },

    parent: function() {
        return DOM(this[0].parentNode);
    }
});
