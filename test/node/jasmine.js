/**
 * Create the Jasmine test runner,
 * and make sure that it uses our ES6 stuff.
 */

// Babel
var root = require("find-root")();
var babelConf = require(root+"/app/System/Config/babel");
require("babel-register")(babelConf);

// Jasmine.
var JasmineEngine = require("jasmine");
var util = require("util");
var J = new JasmineEngine();

J.loadConfig({
    spec_dir: "test/node",
    spec_files: [
        "tests/*.test.js"
    ],
    helpers: [
        "jasmine.js"
    ]
});

J.configureDefaultReporter({print: function(){}});

var specReporter = require("jasmine-spec-reporter");
jasmine.getEnv().addReporter(new specReporter());

J.execute();
