var fs=require("fs");
module.exports = function() {
    // Dynamically write this config.
    var obj = {
        tasks: {
            "*": [
                "cd '"+config.base+"'",
                "git pull",
                "git submodule update",
                "npm install",
                "composer install",
                "node node-lib/updater.js '%m'"
            ],
        }
    }, str = JSON.stringify(obj), glConf = config.base+"/config/gitlabhook.json";

    BIRD3.info("BIRD3 Auto updater: Generating config to "+glConf);
    fs.writeFileSync(glConf, str);

    // Set it up
    var gitlabhook = require("gitlabhook"),
        gitlab = gitlabhook({
            host: config.BIRD3.host,
            configFile: "gitlabhook.json",
            configPathes: [ config.base+"/config" ],
            logger: log,
        });

    BIRD3.info("BIRD3 Auto updater: Starting");
    gitlab.listen();
    BIRD3.onRedis("BIRD3.update", function(message){
        BIRD3.info("BIRD3 Auto updater -> "+message);
        BIRD3.info("BIRD3 Auto updater: Exiting to allow update.");
        process.exit(2);
    });
    BIRD3.info("BIRD3 Auto Updater -> Online!");
}
