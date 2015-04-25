var lu = require("loader-utils");
var path = require("path");
var exec = require("child_process").exec;
var sh = require("shelljs");

// Compiler
module.exports = function OJ(source,map) {
    if(sh.which("php")===null) {
        throw new Error("In order to use the WingStyle loader, install PHP.");
    } else {
        this.cacheable();
        var cb = this.async();
        if(!cb) {
            throw new Error("The WingStyle loader needs to run async!");
        }
        exec(
            [sh.which("php"), this.resourcePath].join(" "),
            {
                env: process.env,
            },
            function(err, stdout, stderr) {
                if(err) return cb(err);
                if(stderr.length > 0) return cb(new Error(stderr));
                cb(err,stdout);
            }
        );
    }
};
