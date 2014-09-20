var util = require("util");
module.exports = function(title, client) {
    client.on("error",function(e){
        log.error(title+" caught an error: ");
        console.error(e.stack);
        BIRD3.emit("error", null);
    });
}
