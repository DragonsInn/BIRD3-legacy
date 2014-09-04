var helper = require("./default_error.js");
module.exports = function(redis) {
    var heading = "BIRD3";
    log.info(heading+" status watcher: Starting...");
    var client = redis.createClient();
    helper(heading, client);
    client.on("subscribe", function(channel, count){
        log.info(heading+" Status watcher -> Online on '"+channel+"'!");
    });
    client.on("message", function(ch, msg){
        if(ch != heading) return;
        var obj = JSON.parse(msg);
        log.info(heading+" Status -> Got: "+obj.type+"("+JSON.stringify(obj.data)+")");
        BIRD3.emit(obj.type, obj.data);
    });

    client.subscribe(heading);
};
