module.exports.run = function (worker) {
    // Load up the config
    // Initialize the config object.
    var ini = require("multilevel-ini"),
        fs = require("fs"),
        path = require("path");
    var base = path.normalize(path.join(
        __dirname, "..", ".."
    ));
    global.config = ini.getSync(path.join(base, "config/BIRD3.ini"));
    config.base = base;
    var me = require("package")(config.base);
    config.version = me.version;
    config.package = me;

    // Be secure
    require("../security_handler.js")();

    console.log('   >> Worker PID:', process.pid);

    var app = require('express')();

    // Get a reference to our raw Node HTTP server
    var httpServer = worker.getHTTPServer();
    // Get a reference to our realtime SocketCluster server
    var scServer = worker.getSCServer();
    // Initialize the BIRD3 connector
    global.BIRD3 = require("../communicator.js")(scServer);
    var rpc = BIRD3.rpc;
    rpc.addSync("foo", function(){
        return arguments;
    });

    /*
    app.use(function(req, res, next){
        BIRD3.info("Starting: "+req.method+" | "+req.url);
        res.on("finish", function(){
            BIRD3.info(req.ip+"> "+req.method+" "+res.statusCode+": "+req.url);
        });
        return next();
    });
    */

    // Front-end specific
    require("../front-end/request_handler.js")(app);

    httpServer.on('request', app);

    /*
        In here we handle our incoming realtime connections and listen for events.
    */
    scServer.on('connection', function (socket) {
        socket.on('ping', function (data) {
            console.log('PING', data);
            scServer.global.publish('pong', data);
        });

        socket.on('disconnect', function () {
            BIRD3.notice("Client disconnected.");
        });
    });
};
