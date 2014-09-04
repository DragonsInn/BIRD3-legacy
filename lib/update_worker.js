module.exports = function() {
    var gitlabhook = require("gitlabhook"),
        gitlab = gitlabhook({
            host: config.host,
            configFile: config.base+"/config/gitlabhook.json",
            logger: log,
            tasks: {
                "BIRD3": [
                    "cd '"+config.base+"'",
                    "git pull",
                    "git submodule update",
                    "npm install",
                    "node lib/updater.js '%m'"
                ],
            }
        }, function(){});

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
