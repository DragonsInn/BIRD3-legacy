var oo = require("./core");
var modules = {
    dom: require("./dom"),
    ajax: require("./ajax")
};
for(var mname in modules) {
    var mod = modules[mname];
    oo.publish( mname, mod );
}
module.exports = oo;
