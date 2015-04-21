var sh = require('shelljs');
var spawn = require("child_process").spawn;
var fs = require("fs");

module.exports.run = function(conf) {
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
            config.base+"/php-lib/request_handler.php",
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
            BIRD3.emitRedis("bird3.exit","PHP exited: "+e);
            process.exit(1);
        });
    }
}
