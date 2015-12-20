// Provide a full BIRD3 object that can be included by submodules.
var _root = require("find-root")(),
    path = require("path"),
    YAML = require("yamljs");

var BIRD3 = module.exports = {
    // The root of the app, one level above .../app
    root: _root,

    // NPM, Bower and Composer
    package: require(path.join(_root, "package.json")),
    composer: require(path.join(_root, "composer.json")),
    bower: require(path.join(_root, "bower.json")),

    // Loading the global BIRD3 config.
    config: YAML.parseFile(path.join(BIRD3.root, "config/BIRD3.yml")),

    // Logger
    log: require("../Backend/Log"),

    // The key to share WebPack data on
    WebPackKey: "BIRD3.WebPack",

    // The session key-prefix used to track sessions
    sessionKey: "BIRD3.Session.",

    // The prefix used to retrive the hprose port.
    hprosePortKey: "BIRD3.hprosePort",

    // Max workers...
    maxWorkers: require("os").cpus().length
};
