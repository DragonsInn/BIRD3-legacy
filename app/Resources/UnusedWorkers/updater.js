var redis = require("redis"),
    io = require("socket.io")(),
    BIRD = require("./communicator.js")(io,redis),
    ini = require("multilevel-ini"),
    config = ini.getSync(__dirname+"/../config/BIRD3.ini"),
    fs = require("fs"),
    child_process = require("child_process"),
    spawn = child_process.spawn;

BIRD3.info("-- Gracefuly exiting BIRD3...");

var msg = process.argv[2] || "<no message>";

BIRD.emitRedis("BIRD3.update", msg);

setTimeout(function(){
    while(fs.existsSync(__dirname+"/../cache/BIRD3.pid")) {}

    // The file should be gone now. Cause it to restart.
    var up = spawn(process.argv[0], ["--harmony_proxies", __dirname+"/../app.js"], {
        detached: true,
        stdio: "ignore"
    });
    up.on("error", function(e){
        fs.writeFileSync(__dirname+"/../update.log", err);
    });

    // Lets notify that the update has been done successfuly.
    BIRD.onRedis("BIRD3.status", function(data){
        if(data == "online") {
            BIRD.emit("BIRD3.status", "updated");
            BIRD3.info("-- Done. Exiting");
            process.exit(0);
        }
    });

    // Fallback. Needs to be prettier...
    setTimeout(function(){
        BIRD3.error("-- Something bad happened...");
        process.exit(-1);
    }, 1000*5);
}, 1000);
