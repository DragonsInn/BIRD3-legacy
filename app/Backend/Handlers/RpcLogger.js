var BIRD3 = require("BIRD3/Support/GlobalConfig");
var comm = require("BIRD3/Backend/Communicator")(null, require("redis"));
var rootLog = BIRD3.log.makeGroup(false);

module.exports = function() {
    comm.onRedis("rpc.log", function(o){
        var useLog = rootLog;
        if(typeof o.prefix != "undefined") {
            // Emit with a prefix.
            useLog = BIRD3.log.makeGroup(o.prefix);
        }
        if(typeof useLog[o.method] != "undefined") {
            useLog[o.method].apply(useLog, o.args);
        }
    });
}
