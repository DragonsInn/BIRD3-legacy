var redisP = require("redis");
var redis = redisP.createClient();
var house = require("powerhouse")();
var webpack = require("webpack");
var path = require("path");
var config = require(path.resolve(__dirname,"..","util","webpack.config"));
var BIRD3 = require("./communicator")(null,redisP);
var fs = require("fs");

module.exports.run = function(conf) {
    BIRD3.info("BIRD3 WebPack: Starting compiler...");
    var key = conf.config;
    fs.readFile(__dirname+"/../cache/webpack-hash.txt", function(err,ch){
        if(err) {
            BIRD3.warn("Can not read cache/webpack-hash.txt");
            BIRD3.warn(err);
        } else {
            BIRD3.info("Using this WebPack key: "+ch);
            redis.set(key, ch);
        }
    });
    var compiler = webpack(config);
    var watcher = compiler.watch({
        aggregateTimeout: config.watchDelay,
        poll: true
    }, function(err,stats){
        if(err) throw err;
        console.log(stats.toString({
            colors: true,
            version: true,
            assets: true,
            timings: true,
            //reasons: true,
            errorDetails: true
        }));
        var out = stats.toJson({hash:true});
        var hash = out.hash;
        redis.set(key, hash);
    });

}
