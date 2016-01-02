var domEvents = require("dom-events");

function binder(method) {
    return function() {
        // i.e.: on("name", cb(...))
        var args = Array.prototype.slice.call(arguments);
        this.each(function(node){
            var thisArgs = args.slice(0);
            thisArgs.unshift(node);
            domEvents[method].apply(domEvents, thisArgs);
        });
    }
}

// Little extras
function ooEvent(){
    // noop
};
ooEvent.domEvents = domEvents;
ooEvent.prototype.trigger = binder("emit");
Object.keys(domEvents).forEach(function(method){
    ooEvent.prototype[method] = binder(method);
});

module.exports = ooEvent;
