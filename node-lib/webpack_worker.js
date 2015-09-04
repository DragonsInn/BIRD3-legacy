var redisP = require("redis");
var redis = redisP.createClient();
var house = require("powerhouse")();
var webpack = require("webpack");
var path = require("path");
var config = require(path.resolve(__dirname,"..","util","webpack.config"));
var BIRD3 = require("./logger")(path.join(__dirname, ".."));
var fs = require("fs");
var async = require("async");

var WebPackWorker = function(conf) {
    BIRD3.info("BIRD3 WebPack: Starting compiler...");
    var key = conf;
    async.parallel([
        function(step) {
            fs.readFile(__dirname+"/../cache/webpack-hash.txt", function(err,ch){
                if(err) {
                    BIRD3.warn("Can not read cache/webpack-hash.txt");
                    return step(err);
                } else {
                    BIRD3.info("Using this WebPack key: "+ch);
                    redis.set(key, ch);
                    step();
                }
            });
        }
    ], function(err){
        if(err) {
            BIRD3.error("There was an error within WebPack.");
            BIRD3.error(err);
            process.exit(1);
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
                redis.set(key, hash);
            });

            // shutdown
            var down = false;
            house.addShutdownHandler(function(ctx, next){
                if(down) return;
                BIRD3.info("Shuting down WebPack.");
                watcher.close(function(){
                    down = true;
                    BIRD3.info("WebPack is shut down.");
                    next();
                });
            });
        }
    })
}

WebPackWorker(JSON.parse(process.env.POWERHOUSE_CONFIG).config);
