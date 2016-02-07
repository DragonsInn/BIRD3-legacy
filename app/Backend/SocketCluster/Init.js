module.exports.run = function(thisWorker) {

    // Time to bootstrap workers.

    // Put a global BIRD3 object in place
    global.BIRD3 = require("../../Support/GlobalConfig");
    //if(thisWorker.kind == "worker") { require("../Communicator")(redis, sc); }

    // Enable OJ support inside NodeJS
    require("oj-node");

    // Bring in babel
    require("../../bootstrap/nodejs/autoload");

    // Enable Uniter support
    //require("uniter-node");

}
