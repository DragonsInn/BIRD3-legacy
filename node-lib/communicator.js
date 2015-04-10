var io_router = require('socket.io-events')();
var events = require("events");
var avs = require("avs-rpc");
var extend = require("util")._extend;
module.exports = function(io, redis) {
    var obj={};
    obj.channel   = channel = "BIRD3";
    var subscriber = redis.createClient();
    var publisher  = redis.createClient();
    var evt        = new events.EventEmitter();

    log.info("BIRD3 Events: Initializing...");

    subscriber.subscribe(channel);

    // Add event emitter
    obj.on = evt.on;
    obj.emit = evt.emit;
    obj.once = evt.once;

    // Mixed-matter
    obj.onIO = io_router.on;
    obj.emitIO = io.emit;
    obj.onRedis = function(name, cb) {
        subscriber.on("message", function(ch, msg){
            if(ch!=channel) return;
            var o = JSON.parse(msg);
            if(o.name == name) cb(o.data);
        });
    };
    obj.emitRedis = function(name, data) {
        publisher.publish(channel, JSON.stringify({
            name: name,
            data: data
        }));
    };
    obj.onAll = function(name, cb) {
        io_router.on("*", function(sock, args, next){
            var name = args.shift(), msg = args.shift();
            cb(name, msg);
        });
        subscriber.on("message", function(ch, msg){
            var o = JSON.parse(msg);
            cb(o.name, o.data);
        });
        this.on(name, cb);
    };
    obj.emitAll = function(name, data) {
        this.emitRedis(name, data);
        this.emitIO(name, data);
        this.emit(name, data);
    };

    // Add the router
    io.use(io_router);

    // Make it public:
    obj.io = io;
    obj.ior = io_router;
    obj.redis = {
        subscriber: subscriber,
        publisher: publisher
    };

    // Create a function and run it - private/public can work that way.
    obj.rpc = (function(){
        // Private members
        var sync_methods = {},
            async_methods = {};

        // Public functions
        this.addSync = function(name, cb) {
            sync_methods[name]=cb;
            io.emit("rpc_init_data", this.methodNames());
        };

        this.addAsync = function(name, cb) {
            async_methods[name]=cb;
            io.emit("rpc_init_data", this.methodNames());
        };

        this.methodNames = function() {
            return Object.keys(extend(sync_methods, async_methods));
        };

        this.implementTo = function(rpcObj) {
            rpcObj.implement(sync_methods);
            rpcObj.implementAsync(async_methods);
        };

        return this;
    })();
    io.on("connection", function(sock){
        var rpc = new avs.ioRpc(sock);
        obj.rpc.implementTo(rpc);
        sock.on("rpc_init", function(){
            sock.emit("rpc_init_data", obj.rpc.methodNames());
        });
    });

    // Add the basics
    io_router.on("*", function(sock, args, next){
        //console.log("*: ", arguments);
        var name = args[0], msg = args[1];
        log.info("BIRD3 Events (Socket.IO): "+name+"("+msg+")");
        return next();
    });
    subscriber.on("message", function(ch, msg){
        var o = JSON.parse(msg);
        log.info("BIRD3 Events (Redis: "+ch+"): "+o.name+'('+JSON.stringify(o.data)+')');
    });

    process.on('uncaughtException', function(err){
        obj.emit("error", err);
    });

    return obj;
}
