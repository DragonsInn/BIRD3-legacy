var ev = module.exports = function ooEvent(target, name, cb) {
    if(!(this instanceof ev)) {
        return new ev(target, name, cb);
    }
    // Determine arguments
    if(typeof target == "string" && typeof name == "function") {
        cb = name;
        name = target;
        target = window;
    } else if(typeof target == "function") {
        cb = target;
        name = ""; // DOM is ready event
        target = window;
    } else if(typeof target == "object" && typeof name == "string" && typeof cb == "function") {
        // Okay.
    } else if(typeof target == "object" && typeof name == "undefined" && typeof cb == "undefined") {
        // This is a mustered object. This is fine.
        this.__target = target;
        return this;
    } else {
        throw new Error("Wrong arguments supplied.");
    }

    // Attach the event and such.
    this.__store = target;
    this.on(name, cb);
};

ev.prototype = {
    __target: null,
    on: function(name, cb) {
        if(target.addEventListener) {
            target.addEventListener(name, cb);
        } else {
            throw new Error("Whoah...?! I need to work on this.");
        }
    }
}
