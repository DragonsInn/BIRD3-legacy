module.exports.run = function(workerConf, house) {
    var redisP = require("redis");
    var redis = redisP.createClient();
    var webpack = require("webpack");
    var path = require("path");
    var BIRD3 = require("BIRD3/Support/GlobalConfig");
    var log = BIRD3.log;
    var config = require("BIRD3/System/Config/webpack");
    var fs = require("fs");
    var async = require("async");

    log.info("BIRD3 WebPack: Starting compiler...");
    async.parallel([
        function(step) {
            fs.readFile(path.join(BIRD3.root, "cache/webpack-hash.txt"), function(err,ch){
                if(err) {
                    log.warn("Can not read cache/webpack-hash.txt");
                    return step(err);
                } else {
                    log.info("Using this WebPack key: "+ch);
                    redis.set(BIRD3.WebPackKey, ch);
                    step();
                }
            });
        }
    ], function(err){
        if(err) {
            log.error("There was an error within WebPack.");
            log.error(err);
            BIRD3.emitRedis("bird3.exit", err);
        } else {
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
                redis.set(BIRD3.WebPackKey, hash);
            });

            // shutdown
            var down = false;
            house.addShutdownHandler(function(ctx, next){
                if(down) return;
                log.info("Shuting down WebPack.");
                watcher.close(function(){
                    down = true;
                    log.info("WebPack is shut down.");
                    next();
                });
            });
        }
    })
}
require("powerhouse")();
