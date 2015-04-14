var fs = require("fs");
var util = require("util");
var wrench = require("wrench");

function doDirs(uid, gid) {
    var dirs = [
        config.base+"/cache",
        config.base+"/cdn/assets",
        config.base+"/config"
    ];
    for(var i=0; dirs.length > i; i++) {
        var dir = dirs[i];
        BIRD3.info("BIRD3 Security -> "+dir+"...");
        if(!fs.existsSync(dir)) fs.mkdirSync(dir, 0777);
        if(!fs.writeFileSync(dir+"/.tmp", "o.o")) {
            wrench.chmodSyncRecursive(dir, 0777);
            //fs.unlink(dir+"/.tmp", function(){});
        }
        wrench.chownSyncRecursive(dir, uid, gid);
    }
}

module.exports = function() {
    BIRD3.info("BIRD3 Security: Setting up");

    if(typeof config.BIRD3.userName != "undefined" && typeof config.BIRD3.groupName != "undefined") {
        if(process.setuid && process.setgid) {
            // First things first, we need to become the other user.
            var userid = require("userid");
            var uName = config.BIRD3.userName;
            var gName = config.BIRD3.groupName;
            var uid = userid.uid(uName);
            var gid = userid.gid(gName);

            BIRD3.info("BIRD3 Security -> Preparing to change to "+uName+":"+gName+" ("+uid+":"+gid+")")

            doDirs(uid, gid);

            // Become that.
            process.setgid(gid);
            process.setuid(uid);

            BIRD3.info("BIRD3 Security -> Changed.");

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
