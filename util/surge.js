#!/usr/bin/env node
// Customized surge tool
var surge = require("surge")({ default: "publish" });
var BIRD3 = require("../app/Support/GlobalConfig");
var path = require("path");

var r = function() {
    return path.normalize(path.join.apply(path, arguments));
}

var argv = process.argv.slice(2);
argv.push("--domain="+BIRD3.config.CDN.domain);
argv.push("--project="+r(BIRD3.root, "cdn/app"));

console.log("> surge "+argv.join(" "));

// Call surge
surge(argv);

// Nice error message on exit.
process.on('SIGINT', function() {
  console.log("\n")
  global.ponr == true
    ? console.log("    Disconnected".green, "-", "Past point of no return, completing in background.")
    : console.log("    Cancelled".yellow, "-", "Upload aborted, publish not initiated.")
  console.log()
  process.exit(1)
})
