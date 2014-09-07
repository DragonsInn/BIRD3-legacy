var redis = require("redis"),
    client = redis.createClient(),
    ini = require("multilevel-ini"),
    config = ini.getSync(__dirname+"/../config/BIRD3.ini"),
    fs = require("fs"),
    child_process = require("child_process"),
    spawn = child_process.spawn;

console.log("-- Gracefuly exiting BIRD3...");
var data = JSON.stringify({
    type: "update",
    data: process.argv[1]
});

client.on("ready", function(){
    client.publish("BIRD3", data);

    while(fs.existsSync(__dirname+"/../cache/BIRD3.pid")) {
        process.stdout.write(".");
    }

    // The file should be gone now. Cause it to restart.
    spawn(process.argv[0], [__dirname+"/../app.js"], {
        detached: true,
        stdio: ['ignore', 'ignore', 'ignore']
    });

    // Lets notify that the update has been done successfuly.
    client.on("message", function(ch, msg){
        if(ch=="BIRD3") {
            var obj = JSON.parse(msg);
            if(obj.type == "status" || obj.data == "online") {
                client.submit("BIRD3", JSON.stringify({
                    type: "status",
                    data: "updated"
                }));
                console.log("-- Done. Exiting");
                process.exit(0);
            }
        }
    });
    setTimeout(function(){
        console.error("-- Something bad happened...");
        process.exit(-1);
    }, 1000*5);
});
