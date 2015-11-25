var BIRD3 = require("BIRD3/Support/GlobalConfig");
var comm = require("BIRD3/Backend/Communicator")(null, require("redis"));

module.exports = function() {
    comm.onRedis("rpc.log", function(o){
        if(typeof BIRD3.log[o.method] != "undefined") {
            BIRD3.log[o.method].apply(BIRD3.log, o.args);
        }
    });
}
