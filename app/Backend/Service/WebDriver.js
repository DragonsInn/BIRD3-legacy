import WebDriverHost from "BIRD3/Foundation/WebDriver/Host";
import BIRD3 from "BIRD3/Support/GlobalConfig";
import Communicator from "BIRD3/Backend/Communicator";
import redis from "redis";
import path from "path";

var log = BIRD3.log.makeGroup("WebDriver");
var comm = Communicator(null, redis);

export function run(conf, house) {
    var host = WebDriverHost({
        procClass: "BIRD3\\Foundation\\ServerApplication",
        composerFile: path.join(BIRD3.root, "php_modules/autoload.php"),
        // $host, $port, $name
        args: [
            "127.0.0.1",
            conf.config.hprose,
            4,
            "WebDriver (hprose)"
        ],
        cwd: path.join(BIRD3.root, "app"),

        isWatching: true,
        watchPath: BIRD3.root,
        watchPattern: [
            "app/App/**/*.php",
            "app/Backend/**/*.php",
            "app/Foundation/**/*.php",
            "app/Extensions/**/*.php",
            "app/Support/**/*.php",
            "app/Resources/**/*.php"
        ]
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
        log.info("Shutting down");
        host.kill();
        next();
    });
}
