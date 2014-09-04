var fs = require("fs");

function doDirs(uid, gid) {
    var dirs = [config.base+"/cache", config.base+"/cdn/assets"];
    for(var i=0; dirs.length <= i; i++) {
        var dir = dirs[i];
        if(!fs.existsSync(dir)) fs.mkdirSync(dir, 755);
        if(!fs.writeFileSync(dir+"/.tmp", "o.o")) {
            fs.chmodSync(dir, 755);
            fs.unlink(dir+"/.tmp", function(){});
        }
        fs.chownSync(dir, uid, gid);
    }
}

module.exports = function() {
    log.info("BIRD3 Security: Setting up");

    if(typeof config.userName != "undefined" && typeof config.groupName != "undefined") {
        if(process.setuid && process.setgid) {
            // First things first, we need to become the other user.
            var userid = require("userid");
            var uid = userid.uid(config.userName);
            var gid = userid.gid(config.groupName);

            doDirs(uid, gid);

            // Become that.
            process.setuid(uid);
            process.setgid(gid);

            log.info("BIRD3 Security -> Changed to "+config.userName+":"+config.groupName+" ("+uid+":"+gid+")");

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
