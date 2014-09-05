// In order to obtain all information that we need:
process.env["DEBUG"]="socket.io:*";
process.title="BIRD3";

var app = require('http').createServer();
// Performance tweak.
app.globalAgent.maxSockets = 100;

var io = require('socket.io')(app);
var fs = require('fs');
var winston = require("winston");
var redis = require("redis");
var events = require("events");


// Initialize the config object.
global.config = require("./config/nodejs");
config.base = __dirname;

// Global eventing.
global.BIRD3 = new events.EventEmitter();

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

// make the server listen
app.listen(config.http_port, config.host);

// Set up the web stuff.
require("./lib/security_handler.js")();
require("./lib/request_handler.js")(app);
require("./lib/status_worker.js")(redis);
require("./lib/update_worker.js")();
require("./lib/websocket_handler.js")(io);

// Default event.
BIRD3.on("error", function(){ process.exit(1); });

// Watch over everything
var client = redis.createClient();
client.subscribe("BIRD3 Status");
client.on("message", function(ch, msg){
    log.info("Redis: "+ch+": "+msg);
});
