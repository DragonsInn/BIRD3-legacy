var root = require("find-root")();
var path = require("path");
module.exports = {
    presets: ['es2015', 'stage-1'],
    plugins: [
        ["transform-es2015-modules-commonjs", {
            allowTopLevelThis: true
        }],
        ["module-alias",[
            { src: path.join(root, "app"), expose: "BIRD3" }
        ]],
        ["add-module-exports"]
    ], //"transform-runtime"
}
