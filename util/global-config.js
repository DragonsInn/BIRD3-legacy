/**
 * Access the global config from the command line.
 */

var root = require("find-root")()+"/app";
var BIRD3 = require(root+"/Support/GlobalConfig");
var reach = require("reach");
var config = BIRD3.config;

if(!process.argv[2]) {
    throw new Error("Argument required. Usage: node utils/global-config key");
}

console.log(reach(config, process.argv[2]));
