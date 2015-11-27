var domEvents = require("dom-events");

function binder(method) {
    return function() {
        // i.e.: on("name", cb(...))
        var args = Array.prototype.splice.call(arguments);
        this.each(function(node){
            var thisArgs = args.slice(0).unshift(node);
            domEvents[method].apply(domEvents, thisArgs);
        });
    }
}

// Little extras
module.exports = function Event(){
    // noop
};
module.exports.prototype.trigger = binder("emit");
Object.keys(domEvents).forEach(function(method){
    module.exports.prototype[method] = binder(method);
});
