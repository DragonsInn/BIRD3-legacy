module.exports.run = function(conf, house) {
    var WebDriveHost = require("BIRD3/Foundation/WebDriver/Host");
    var BIRD3 = require("BIRD3/Support/GlobalConfig");
    var comm = require("BIRD3/Backend/Communicator")(null, require("redis"));
    var path = require("path");

    var host = WebDriveHost({
        procClass: "BIRD3\\Foundation\\ServerApplication",
        composerFile: path.join(BIRD3.root, "php_modules/autoload.php"),
        // $host, $port, $name
        args: [
            "127.0.0.1",
            conf.config.hprose,
            4,
            "WebDriver (hprose)"
        ],
        cwd: path.join(BIRD3.root, "app")
    });
    host.on("error", function(e){
        host.kill();
        comm.emitRedis("bird3.exit", e);
    });
    host.on("exit", function(code, signal){
        host.kill();
        var msg = "PHP exited: ("+[code, signal].join(", ")+")";
        comm.emitRedis("bird3.exit", msg);
    });

    house.addShutdownHandler(function(ctx, next){
        if(ctx.event != "exit") return next();
        BIRD3.log.info("Shutting down WebDriver");
        host.kill();
        next();
    });
}
