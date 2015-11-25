var dom = module.exports = function DOM(sel) {
    // Return a DOM element
    if(!(this instanceof dom)) {
        return new dom(sel);
    }
}

var noop = function(){ throw new Error("Not implemented."); };
dom.prototype = {
    // Element store
    __store: [],

    // Get or set class.
    class: noop,

    // Add or remove class. Return -1 or 1 for off or on.
    toggleClass: noop,

    // Sizes and such
    height: noop,
    width: noop,

    // Simple events
    click: noop,
    focus: noop,

    // Styling
    css: noop
};
