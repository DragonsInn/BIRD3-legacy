// In order to obtain all information that we need:
process.title="BIRD3";

// HTTP, WS, Socket.IO
var http = require('http'),
    cluster = require("cluster"),
    cpus = require("os").cpus().length;
    connect = require("connect"),
    app = connect(),
    connLogger = require("connect-logger"),
    responseTime = require("response-time"),
    io = require('socket.io')();

// Misc
var fs = require('fs'),
    winston = require("winston"),
    redis = require("redis"),
    ini = require("multilevel-ini");

// We use this to dump emergency errors.
process.on("uncaughtException", function(e){
    console.error(e.stack);
    fs.writeFileSync("error.dump", e.stack);
    process.exit(-1);
});

// Logging and configuring it
global.log = new (winston.Logger)({
    transports: [
    new (winston.transports.Console)({
        colorize: true,
        timestamp: true
    }),
    new (winston.transports.File)({
        filename: __dirname+'/log/bird3.log',
        json: false,
        maxsize: 50*1024^2
    })
    ]
});

// Global eventing
global.BIRD3 = require("./lib/communicator.js")(io, redis);

// Initialize the config object.
global.config = ini.getSync("./config/BIRD3.ini");
config.base = __dirname;
config.version = fs.readFileSync("./config/version.txt").toString().replace("\n","");
// Intro!
log.info("BIRD3@"+config.version+" starting up!");

// Configure connect...
app.use(connLogger());
app.use(responseTime());

// make the server listen
var httpServer = app.listen(config.BIRD3.http_port, config.BIRD3.host);
io.listen(httpServer);
httpServer.on("listening", function(){
    log.info("BIRD3 Listening now: "+config.BIRD3.host+":"+config.BIRD3.http_port);
    // Set up the web stuff.
    require("./lib/security_handler.js");
    require("./lib/error_handler.js")();
    require("./lib/request_handler.js")(app, httpServer);
    require("./lib/update_worker.js")();
    // Performance...
});
