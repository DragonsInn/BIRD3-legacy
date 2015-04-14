Error.stackTraceLimit = Infinity;

var session = require("express-session"),
    RedisStore = require('connect-redis')(session),
    sockpress = require("sockpress").init,
    express = require("express"),
    io = require("socket.io")(),
    sioRedis = require("socket.io-redis"),
    redis = require("redis"),
    http = require("http"),
    // FIXME: This will result in a Master->worker->worker scenario...
    // Before i can use this, i need to structure this better.
    MC = require("master-cluster");


function FrontentWorker(conf) {
    var app = sockpress({
        store: new RedisStore({
            prefix: "BIRD3.Session.",
            db: 0
        }),
        name: 'PHPSESSID',
        secret: config.version,
        resave: false,
        saveUninitialized: true
    });

    var setup = function(){
        app.use(function(req, res, next){
            //log.info("Starting: "+req.method+" | "+req.url);
            res.on("finish", function(){
                log.info(req.ip+"> "+req.method+" "+res.statusCode+": "+req.url);
            });
            return next();
        });
        // Basic workers
        require("./security_handler.js");
        require("./error_handler.js")();
        // Front-end specific
        require("./front-end/request_handler.js")(app);
        require("./front-end/live_handler.js")(io, redis);
    };

    global.BIRD3 = require("./communicator.js")(app.io, redis);

    app.io.adapter(sioRedis());
    //app.listen(config.BIRD3.http_port, config.BIRD3.host, );

    // They see me hackin', they hatin'~ :3
    var eListen = app.listen;
    app.listen = function() {
        var args = Array.prototype.slice.call(arguments);;
        args.push(setup);
        return eListen.apply(app, args);
    }
    return app;
}

module.exports = FrontentWorker;
