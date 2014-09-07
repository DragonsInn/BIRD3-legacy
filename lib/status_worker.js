module.exports = function(redis) {
    var heading = BIRD3.channel;

    log.info(heading+" status watcher: Starting...");

    BIRD3.onAll(function(type, data){
        log.info(heading+" Status -> "+type+"("+JSON.stringify(data)+")");
    });

    BIRD3.emit("status", "online");

    log.info(heading+" Status watcher -> Online on '"+channel+"'!");
};
