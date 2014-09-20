var sio_router = require("socket.io-events")();
module.exports = function(io) {
    log.info("BIRD3 Socket.IO: Starting");

    sio_router.on('*', function(sock, args, next) {
        var name = args.shift(), msg = args.shift();
        log.info("BIRD3 Socket.IO -> "+name+"("+JSON.stringify(msg)+")");
    });

    // Use it
    io.use(sio_router);

    log.info("BIRD3 Socket.IO: Listening for all events now.");
}
