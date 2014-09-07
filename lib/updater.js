var BIRD = require("./communicator.js");
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

BIRD.emit("update", process.argv[1]);

while(fs.existsSync(__dirname+"/../cache/BIRD3.pid")) {
    process.stdout.write(".");
}

// The file should be gone now. Cause it to restart.
spawn(process.argv[0], [__dirname+"/../app.js"], {
    detached: true,
    stdio: ['ignore', 'ignore', 'ignore']
});

// Lets notify that the update has been done successfuly.
BIRD.on("status", function(data){
    if(data == "online") {
        BIRD.emit("status", "updated");
        console.log("-- Done. Exiting");
        process.exit(0);
    }
});

// Fallback. Needs to be prettier...
setTimeout(function(){
    console.error("-- Something bad happened...");
    process.exit(-1);
}, 1000*5);
