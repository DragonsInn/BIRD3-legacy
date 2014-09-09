var fs = require("fs");
var util = require("util");

function doDirs(uid, gid) {
    var dirs = [
        config.base+"/cache",
        config.base+"/cdn/assets",
        config.base+"/config"
    ];
    for(var i=0; dirs.length > i; i++) {
        var dir = dirs[i];
        log.info("BIRD3 Security -> "+dir+"...");
        if(!fs.existsSync(dir)) fs.mkdirSync(dir, 0744);
        if(!fs.writeFileSync(dir+"/.tmp", "o.o")) {
            fs.chmodSync(dir, 0744);
            fs.unlink(dir+"/.tmp", function(){});
        }
        fs.chownSync(dir, uid, gid);
    }
}

module.exports = function() {
    log.info("BIRD3 Security: Setting up");

    if(typeof config.BIRD3.userName != "undefined" && typeof config.BIRD3.groupName != "undefined") {
        if(process.setuid && process.setgid) {
            // First things first, we need to become the other user.
            var userid = require("userid");
            var uName = config.BIRD3.userName;
            var gName = config.BIRD3.groupName;
            var uid = userid.uid(uName);
            var gid = userid.gid(gName);

            doDirs(uid, gid);

            // Become that.
            process.setgid(gid);
            process.setuid(uid);

            log.info("BIRD3 Security -> Changed to "+uName+":"+gName+" ("+uid+":"+gid+")");

        } else {
            info.warn("BIRD3 Security -> Can not change UID/GID!");
            doDirs(process.uid, process.gid);
        }
    }

    // Create a .pid file for stuff...
    fs.writeFileSync(config.base+"/cache/BIRD3.pid", process.pid);

    process.on("exit",function(){
        fs.unlinkSync(config.base+"/cache/BIRD3.pid");
    });
};
