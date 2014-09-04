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
                "node lib/updater.js '%m'"
            ],
        }
    }, str = JSON.stringify(obj), glConf = config.base+"/config/gitlabhook.json";

    log.info("BIRD3 Autp updater: Generating config to "+glConf);
    fs.writeFileSync(glConf, str);

    // Set it up
    var gitlabhook = require("gitlabhook"),
        gitlab = gitlabhook({
            host: config.host,
            configFile: "gitlabhook.json",
            configPathes: [ config.base+"/config" ],
            logger: log,

        });

    log.info("BIRD3 Auto updater: Starting");
    gitlab.listen();
    BIRD3.on("update", function(){
        setTimeout(function(){
            log.info("BIRD3 Auto updater: Exiting to allow update.");
            process.exit(2);
        }, 200);
    });
    log.info("BIRD3 Auto Updater -> Online!");
}
