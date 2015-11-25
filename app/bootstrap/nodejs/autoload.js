// Make BIRD3 a module.
var root = require("find-root")();
var join = require("path").join;
var appDir = join(root, "app");
var modules = join(root, "node_modules");
if(!require("fs").existsSync(modules+"/BIRD3")) {
    require("fs").symlinkSync(appDir, modules+"/BIRD3", "dir");
}
