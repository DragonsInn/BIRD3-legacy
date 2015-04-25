Error.stackTraceLimit = Infinity;

var session = require("express-session"),
    RedisStore = require('connect-redis')(session),
    express = require("express"),
    socketio = require("socket.io"),
    sioRedis = require("socket.io-redis"),
    redis = require("redis"),
    http = require("http"),
    // FIXME: This will result in a Master->worker->worker scenario...
    // Before i can use this, i need to structure this better.
    //MC = require("master-cluster"),
    sticky = require("sticky-session"),
    house = require("powerhouse")();

(function FrontentWorker(conf) {
    global.config = global.config || conf;

    // Make it sticky
    return sticky(conf.maxWorkers, function(){
        var sessionSettings = {
            store: new RedisStore({
                prefix: "BIRD3.Session.",
                db: 0
            }),
            name: 'PHPSESSID',
            secret: config.version,
            resave: false,
            saveUninitialized: true
        };
        var app = express();
        var io = socketio();
        var server = http.createServer(app);
        io.listen(server);
        io.adapter(sioRedis());
        global.BIRD3 = require("./communicator.js")(io);
        server.on("listening",function(){
            app.use(function(req, res, next){
                BIRD3.info("Starting: "+req.method+" | "+req.url);
                res.on("finish", function(){
                    BIRD3.info(req.ip+"> "+req.method+" "+res.statusCode+": "+req.url);
                });
                return next();
            });
            // Basic workers
            require("./security_handler.js")();
            // Front-end specific
            require("./front-end/request_handler.js")(app);
        });

        return server;
    }).listen(
        config.BIRD3.http_port,
        config.BIRD3.host//, setup
    );
})(JSON.parse(process.env.POWERHOUSE_CONFIG).config);
