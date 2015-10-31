// core
require("../app/bootstrap/autoload");
var path = require("path");
var webpack = require("webpack");

// Config stuff
var config = require("BIRD3/Support/GlobalConfig").config;
var wpConf = require("./webpack.config");

// Args
var argv = require("yargs").argv;

switch(argv._[0]) {
    case "compile":
        var compiler = webpack(wpConf);
        compiler.run(function(err, stats){
            console.log(stats.toString({
                modulesSort: "size",
                colors: true,
                version: true,
                timings: true,
                //assets: true,
                //reasons: true,
                //errorDetails: true
            }));
        });
    break;
}
