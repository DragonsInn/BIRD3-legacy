// In order to obtain all information that we need:
process.env["DEBUG"]="socket.io:*";

var app = require('http').createServer();
var io = require('socket.io')(app);
var fs = require('fs');
var winston = require("winston");

// Initialize the config object.
global.config = {
    base: __dirname
};
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
app.listen(8080, "localhost");

// Set up the web stuff.
var request_handler = require("./lib/request_handler.js");
var ws_handler = require("./lib/websocket_handler.js");

// Register the handlers.
app.on("request", request_handler);
io.on('connection', ws_handler);

log.info("BIRD3 now running.");
