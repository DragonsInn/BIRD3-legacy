var sh = require('shelljs');
var spawn = require("child_process").spawn;
var fs = require("fs");
var BIRD3 = require("./communicator")(null, require("redis"));

module.exports.run = function(conf, house) {
    // Try to find PHP...
    var phpBin, config = conf.config;
    if(!sh.which("php") && !sh.which("php-cli")) {
        BIRD3.error("You need PHP installed!");
        BIRD3.emitRedis("bird3.exit");
    } else {
        if(sh.which("php"))
            phpBin = "php";
        else if(sh.which("php-cli"))
            phpBin = "php-cli";

        var args = [
            // Bring up the Workerman app. It's a subserver, so it deserves the name "app.php"
            // Besides, it can be ran independently, technically! (:
            config.base+"/app.php",
            // Tell workerman to go up
            "start",
            // We only want this service localy
            "--host=127.0.0.1",
            // This is generated at app start
            "--port="+config.hprosePort,
            "--workers="+config.maxWorkers*2
        ];
        var opts = {
            cwd: config.base,
            env: process.env,
            stdio: ["ignore", process.stdout, process.stderr]
        };
        var php = spawn(phpBin, args, opts);
        php.on("exit", function(e){
            if(e1=null) {
                BIRD3.emitRedis("bird3.exit","PHP exited: "+e);
            };
            // This process is only here to maintain PHP.
            process.exit(0);
        });

        house.addShutdownHandler(function(){
            BIRD3.info("Shutting down Workerman...");
            php.kill();
        });
    }
}
