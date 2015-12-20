var fs = require("fs");
var util = require("util");
var wrench = require("wrench");
var BIRD3 = require("./logger.js")();
var log = BIRD3.log.makeGroup("Security");

var dirs = [
    config.base+"/cache",
    config.base+"/cdn/assets",
    config.base+"/config"
];

function doDirs(uid, gid) {
    uid = Number(uid);
    gid = Number(gid);
    for(var i=0; i<dirs.length; i++) {
        var dir = dirs[i];
        fs.exists(dir, function(exists){
            if(!exists) {
                fs.mkdirSync(dir, 0755);
            }
            wrench.chmodSyncRecursive(dir, 0755);
            wrench.chownSyncRecursive(dir, uid, gid);
        });
    }
}

module.exports = function() {
    log.debug("BIRD3 Security: Setting up");

    if(typeof config.BIRD3.userName != "undefined" && typeof config.BIRD3.groupName != "undefined") {
        try {
            // First things first, we need to become the other user.
            var userid = require("userid");
            var uName = config.BIRD3.userName;
            var gName = config.BIRD3.groupName;
            var uid = userid.uid(uName);
            var gid = userid.gid(gName);

            log.debug("BIRD3 Security -> Preparing to change to "+uName+":"+gName+" ("+uid+":"+gid+")")

            doDirs(uid, gid);

            // Become that.
            process.setgid(gid);
            process.setuid(uid);
            log.debug("BIRD3 Security -> Changed.");
        } catch(e) {
            // We cant change perms.
            log.warn("BIRD3 Security -> Can not change UID/GID! (%s)",e);
            doDirs(process.uid, process.gid);
        }
    }

    // Create a .pid file for stuff...
    //fs.writeFileSync(config.base+"/cache/BIRD3.pid", process.pid);

    process.on("exit",function(){
        //fs.unlinkSync(config.base+"/cache/BIRD3.pid");
    });
};
