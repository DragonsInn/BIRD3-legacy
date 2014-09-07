// In order to obtain all information that we need:
process.env["DEBUG"]="socket.io:*";
process.title="BIRD3";

var app = require('http').createServer(),
    io = require('socket.io')(app),
    fs = require('fs'),
    winston = require("winston"),
    redis = require("redis"),
    events = require("events"),
    ini = require("multilevel-ini");


// Initialize the config object.
global.config = ini.getSync("./config/BIRD3.ini");
config.base = __dirname;
config.version = fs.readFileSync("./config/version.txt").toString().replace("\n","");

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

// Intro!
log.info("BIRD3@"+config.version+" starting up!");

// make the server listen
app.listen(config.BIRD3.http_port, config.BIRD3.host);
app.on("listening", function(){
    log.info("BIRD3 Listening now: "+config.BIRD3.host+":"+config.BIRD3.http_port);
    // Set up the web stuff.
    require("./lib/security_handler.js")();
    require("./lib/request_handler.js")(app);
    require("./lib/status_worker.js")(redis);
    require("./lib/update_worker.js")();
    require("./lib/websocket_handler.js")(io);
});

// Default event.
BIRD3.on("error", function(){ process.exit(1); });

// Watch over everything
var client = redis.createClient();
client.subscribe("BIRD3 Status");
client.on("message", function(ch, msg){
    log.info("Redis: "+ch+": "+msg);
});
