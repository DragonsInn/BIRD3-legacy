var redisP = require("redis");
var redis = redisP.createClient();
var house = require("powerhouse")();
var webpack = require("webpack");
var path = require("path");
var config = require(path.resolve(__dirname,"..","util","webpack.config"));
var BIRD3 = require("./communicator")(null,redisP);

module.exports.run = function(conf) {
    BIRD3.info("BIRD3 WebPack: Starting compiler...");
    var key = conf.config;
    var compiler = webpack(config, function(err,state){
        if(err) {
            console.log(err);
            BIRD3.emitRedis("bird3.exit");
        } else {
            BIRD3.info("BIRD3 WebPack: Compiler online. Watching now.");
            // Check every 10 seconds, rebuild then.
            var watcher = compiler.watch(1000*10, function(err,stats){
                if(err) throw err;
                console.log(stats.toString({
                    colors: true,
                    version: true,
                    timings: true,
                    assets: true,
                    reasons: true,
                    errorDetails: true
                }));
                var out = stats.toJson({hash:true});
                var hash = out.hash;
                redis.set(key, hash);
            });
        }
    });
}
