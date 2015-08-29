// Shimming
var oc = Object.create || function(proto) {
    function f() {}
    f.prototype = proto;
    return new f;
};

var oo = module.exports = function oo() {
    if(!(this instanceof oo)) {
        // Create instance
        var obj = oc(oo.prototype);
        return oo.apply(obj, arguments);
    }
    if(arguments.length > 0) {
        return this.__init.apply(this, arguments);
    } else {
        return this;
    }
}

// Override to change behaviour.
oo.prototype.__init = function() {
    // If a real initializer was supplied, use that.
    if(oo.__init) {
        return oo.__init.apply(this, arguments);
    }
    return this;
};

// Publish functions into an object.
oo.publish = function(n,o) {
    if(n.charAt(0) == ".") {
        if(typeof o != "object") {
            throw new TypeError("Prototypes can only be extended by objects. Got: "+typeof o);
        }
        // Edit the prototype
        n = n.slice(1);
        oo[n].prototype = require("merge").recursive(oo[n].prototype, o);
    } else {
        if(typeof o == "function") {
            oo[n] = o;
        } else if(typeof o == "object") {
            oo[n] = require("merge").recursive(oo[n], o);
        } else {
            throw new TypeError("Can only create constructors or merge objects. Got: "+typeof o);
        }
    }
}
