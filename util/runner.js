// core
var path = require("path");

// Paths
var cdn = path.join(__dirname,"..","cdn");
var app = path.join(cdn,"app");
var theme = path.join(__dirname,"..","themes","dragonsinn");
var cache = path.join(__dirname,"..","cache");

// Config stuff
var ini = require("multilevel-ini");
var me = require("package")(path.join(__dirname,".."));
var config = ini.getSync(path.join(__dirname, "..", "config/BIRD3.ini"));
config.base = path.join(__dirname,"..");
config.version = me.version;
config.package = me;
config.maxFileSize = 1024*50;

// Webpack
var webpack = require("webpack");
var wpConf = require("./webpack.config");
var extractText = require("extract-text-webpack-plugin");
var HashPlugin = require('hash-webpack-plugin');
var bowerwp = require("bower-webpack-plugin");
var stripper = require("strip-loader").loader;

// Bower
var bower = require("bower");

// Args
var argv = require("yargs").argv;

// Debug support
global.__debug = argv.debug;

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
