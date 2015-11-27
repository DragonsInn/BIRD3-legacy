var _ = require("microdash");

// Shimming
Object.create = Object.create || (function(proto) {
    function f() {}
    f.prototype = proto;
    return new f;
});

var oo = function() {
    if(!(this instanceof oo)) {
        var inst = Object.create(oo.prototype);
        return oo.apply(inst, arguments);
    }
    if(_.isFunction(this.__init)) {
        return this.__init.apply(this, arguments);
    }
}

// Publish functions into an object.
// @param: publics -> Apply to oo itself.
// @param: privates -> Apply to prototype
oo.publish = function(publics, privates) {
    function makeTypeError(target, desc) {
        var targetType = typeof target;
        throw new Error("Can only "+desc+" with object. Got <"+targetType+"> instead.");
    }
    if(_.isPlainObject(publics) || _.isFunction(publics)) {
        oo = _.extend(oo, publics);
    } else throw makeTypeError(publics, "public members");
    if(_.isPlainObject(privates) || _.isFunction(privates)) {
        oo.prototype = _.extend(oo.prototype, privates);
    } else throw makeTypeError(privates, "private members");
}

// Provide microdash right away
oo.publish(_, {});

// Cheesy (:
// Allows: var o = require("o.o"); o.o(...);
oo.o = oo;

module.exports = oo;
