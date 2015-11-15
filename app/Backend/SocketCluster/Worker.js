module.exports.run = function (worker) {
    process.title = "BIRD3: SC Worker";
    var BIRD3 = require("BIRD3/Support/GlobalConfig");
    var hprosePort = worker.options.workerOptions.hprose;

    // Be secure
    //require("../security_handler.js")();

    var app = require('express')();

    // Get a reference to our raw Node HTTP server
    var httpServer = worker.getHTTPServer();
    // Get a reference to our realtime SocketCluster server
    var scServer = worker.getSCServer();
    // Initialize the BIRD3 connector
    var comm = require("BIRD3/Backend/Communicator")(scServer);
    /* FIXME: Restore API functionality for SC
    require("glob")(path.join(__dirname, "api", "*.js"), function(err, files){
        files.forEach(function(file){
            try {
                require(file)(BIRD3);
            } catch(e) {
                BIRD3.error(e);
            }
        });
    });
    */

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
    app.set('etag', false); // Because. Damn.
    require("BIRD3/Backend/Service/Web")(app, hprosePort);

    httpServer.on('request', app);

    /*
        In here we handle our incoming realtime connections and listen for events.
    */
    scServer.on('connection', function (socket) {
        socket.on('ping', function (data) {
            socket.emit('pong', data);
        });

        socket.on('disconnect', function () {
            //BIRD3.log.notice("Client disconnected.");
        });
    });
};
