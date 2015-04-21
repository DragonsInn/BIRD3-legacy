var fs = require("fs");
var util = require("util");
var wrench = require("wrench");
var debug = require("debug")("bird3:security");
var BIRD3 = require("./communicator.js")();

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
    debug("BIRD3 Security: Setting up");

    if(typeof config.BIRD3.userName != "undefined" && typeof config.BIRD3.groupName != "undefined") {
        try {
            // First things first, we need to become the other user.
            var userid = require("userid");
            var uName = config.BIRD3.userName;
            var gName = config.BIRD3.groupName;
            var uid = userid.uid(uName);
            var gid = userid.gid(gName);

            debug("BIRD3 Security -> Preparing to change to "+uName+":"+gName+" ("+uid+":"+gid+")")

            doDirs(uid, gid);

            // Become that.
            process.setgid(gid);
            process.setuid(uid);
            debug("BIRD3 Security -> Changed.");
        } catch(e) {
            // We cant change perms.
            BIRD3.warn("BIRD3 Security -> Can not change UID/GID! (%s)",e);
            doDirs(process.uid, process.gid);
        }
    }

    // Create a .pid file for stuff...
    //fs.writeFileSync(config.base+"/cache/BIRD3.pid", process.pid);

    process.on("exit",function(){
        //fs.unlinkSync(config.base+"/cache/BIRD3.pid");
    });
};
