// Modules
import {
    createClient as createRedisClient
} from "redis";
import webpack from "webpack";
import path from "path";
import fs from "fs";
import async from "async";

// BIRD3
import BIRD3 from "BIRD3/Support/GlobalConfig";
import WebPackConfig from "BIRD3/System/Config/webpack";
import {ProgressPlugin} from "webpack";

// Vars
var redisClient = createRedisClient();

// Export
export function run(workerConf, house) {
    var log = BIRD3.log.makeGroup("WebPack");

    log.info("Starting compiler...");
    async.parallel([
        (step) => {
            let hashFile = path.join(BIRD3.root, "cache/webpack-hash.txt");
            fs.readFile(hashFile, (err, ch) => {
                if(err) {
                    log.warn("Can not read cache/webpack-hash.txt");
                    return step(err);
                } else {
                    log.info("Using this WebPack key: "+ch);
                    redisClient.set(BIRD3.WebPackKey, ch);
                    step();
                }
            });
        }
    ], (err) => {
        if(err) {
            log.error("There was an error within WebPack.");
            log.error(err);
            BIRD3.emitRedis("bird3.exit", err);
        } else {
            log.info("Injecting process output");
            var progress = new ProgressPlugin((p, msg) => {
                if(p===0) msg = "Starting compilation...";
                if(p===1) msg = "Done!";
                log.update("WebPack => [%s%%]: %s", (p*100).toFixed(2), msg);
            });
            WebPackConfig.plugins.push(progress);

            log.info("Entering watch mode.");
            var compiler = webpack(WebPackConfig);
            var watcher = compiler.watch({
                aggregateTimeout: WebPackConfig.watchDelay,
                poll: true
            }, (err,stats) => {
                if(err) throw err;
                log.update(stats.toString({
                    colors: true,
                    version: true,
                    assets: true,
                    timings: true,
                    //reasons: true,
                    errorDetails: true
                }));
                var out = stats.toJson({hash:true});
                var hash = out.hash;
                redisClient.set(BIRD3.WebPackKey, hash);
            });

            // shutdown
            var down = false;
            house.addShutdownHandler((ctx, next) => {
                if(down) return;
                log.info("Shuting down WebPack.");
                watcher.close(() => {
                    down = true;
                    log.info("WebPack is shut down.");
                    next();
                });
            });
        }
    })
}
require("powerhouse")();
