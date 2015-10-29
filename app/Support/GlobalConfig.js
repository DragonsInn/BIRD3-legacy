// Provide a full BIRD3 object that can be included by submodules.
var _root = require("find-root")(),
    path = require("path");

var BIRD3 = module.exports = {
    // The root of the app, one level above .../app
    root: _root,

    // NPM, Bower and Composer
    package: require(path.join(_root, "package.json")),
    composer: require(path.join(_root, "composer.json")),
    bower: require(path.join(_root, "bower.json")),

    // Config from config/BIRD3.ini
    config: {},

    // Logger
    log: require("BIRD3/Backend/Log")(_root),

    // The key to share WebPack data on
    WebPackKey: "BIRD3.WebPack",

    // The session key-prefix used to track sessions
    sessionKey: "BIRD3.Session.",

    // The prefix used to retrive the hprose port.
    hprosePortKey: "BIRD3.hprosePort",

    // Max workers...
    maxWorkers: require("os").cpus().length
};

// Fill the config
// Initialize the config object.
var ini = require("multilevel-ini"),
    fs = require("fs");

// Load
BIRD3.config = ini.getSync(path.join(BIRD3.root, "config/BIRD3.ini"));
