var purify = require("purify-css");
var merge = require("array-merger").merge;
var glob = require("glob").sync;
var path = require("path");
var ConcatSource = require("webpack/lib/ConcatSource");

module.exports = function PurifyPlugin(options) {
    this.basePath = options.basePath || process.cwd();
    this.purifyOptions = options.purifyOptions || {minify:true, info:true};
    if(options.paths) {
        this.paths = options.paths;
    } else {
        throw new Error("Required: options.paths");
    }
}

module.exports.prototype.apply = function(compiler) {
    var files=[], self=this;
    self.paths.forEach(function(p){
        files = merge(files,glob(path.join(self.basePath, p)));
    });

    compiler.plugin("this-compilation", function(compilation) {
        compilation.plugin("additional-assets", function(cb){
            for(var key in compilation.assets) {
                if(/\.css$/i.test(key)) {
                    // We found a CSS. So purify it.
                    var asset = compilation.assets[key];
                    var css = asset.source();
                    var newCss = new ConcatSource();
                    newCss.add(purify(files, css, self.purifyOptions));
                    compilation.assets[key] = newCss;
                }
            }
            cb();
        });
    });
}
