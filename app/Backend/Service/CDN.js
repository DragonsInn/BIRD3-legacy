/**
 * This automatically deploys to surge.sh, if enabled.
 *
 * It uses the Global config to determine params.
 */

import Communicator from "BIRD3/Backend/Communicator";
import {config, root, log} from "BIRD3/Support/GlobalConfig";
import surge from "surge";
import redis from "redis";

var surgeArgv = [
    "--domain="+config.CDN.domain,
    "--project="+root+"/cdn"
];
var surgeInstance = surge({ default: "publish" });

var logger = log.makeGroup("CDN");
var comm = Communicator(null, redis);

if(config.CDN.enable) {
    logger.info("Setting up auto-deploy to surge.sh (%s).", config.CDN.domain);
    comm.onRedis("webpack.compiled", function(){
        logger.update("Publishing!");
        surgeInstance(surgeArgv);
    });
} else {
    logger.notice("Auto-deploy to surge.sh (%s) is disabled.",config.CDN.domain);
    comm.onRedis("webpack.compiled", function(){
        logger.notice("Not publishing.");
    });
}
