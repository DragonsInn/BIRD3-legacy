var md = require("markdown-it");
var mdConf = require("BIRD3/System/Config/markdown-it");

module.exports = function() {
    var opt = Object.create(mdConf);
    var plugins = opt.use;
    delete opt.use;
    var p = md(opt.preset, opt);
    plugins.forEach(function(plugin){
        p.use(plugin);
    });
    return p;
}
