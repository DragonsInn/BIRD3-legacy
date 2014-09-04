var redis = require("redis"),
    client = redis.createClient(),
    config = require(__dirname+"/../config/nodejs.js"),
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
        console.log(".");
    }

    // The file should be gone now. Cause it to restart.
    spawn(process.argv[0], [__dirname+"/../app.js"], {
        detached: true,
        stdio: ['ignore', 'ignore', 'ignore']
    });
    console.log("-- Done. Exiting");
    process.exit(0);
});
