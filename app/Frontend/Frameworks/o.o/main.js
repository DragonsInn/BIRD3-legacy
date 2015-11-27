var oo = require("./core");
var modules = [
    require("./ajax"),
    require("./dom"),
    require("./events")
];
modules.forEach(function(mod){
    oo.publish( mod, mod.prototype || {} );
});
module.exports = oo;
