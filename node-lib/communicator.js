//var io_router = require('socket.io-events')();
var events = require("events");
var avs = require("avs-rpc");
var extend = require("util")._extend;
var debug = require("debug")("bird3:events");

function dont(m) {
    return function() {
        throw new Error("You may not call "+m+" at this point!");
    }
}

module.exports = function(io, redis) {
    var obj={};
    var evt = new events.EventEmitter();

    // Get the log in.
    var logger = require("./logger.js")(config.base);
    for(var level in logger.levels) {
        obj[level]=logger[level];
    }

    function onRedisError(e) {
        obj.error("Redis error: "+e);
    }

    function makeRedis() {
        obj.channel    = channel = "BIRD3";
        var subscriber = redis.createClient();
        var publisher  = redis.createClient();
        subscriber.on("subscribe", function(ch, c){
            obj.info("BIRD3 has joined Redis channel: "+ch);
        });
        subscriber.on("error", onRedisError);
        subscriber.subscribe(channel);
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
        obj.redis = {
            subscriber: subscriber,
            publisher: publisher
        };

        subscriber.on("message", function(ch, msg){
            var o = JSON.parse(msg);
            obj.info("BIRD3 Events (Redis: "+ch+"): "+o.name+'('+JSON.stringify(o.data)+')');
        });

        // One basic thing to look for. Servely useful.
        obj.onRedis("rpc.log", function(o){
            obj[o.method].apply(obj, o.args);
        });
    }

    function makeIo() {
        // Mixed-matter
        //obj.onIO = io_router.on;
        obj.emitIO = io.emit;

        // Add the router
        //io.use(io_router);

        // Make it public:
        //obj.io = io;
        //obj.ior = io_router;

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
            var rpc = new avs.scRpc(sock);
            obj.rpc.implementTo(rpc);
            sock.on("rpc_init", function(){
                sock.emit("rpc_init_data", obj.rpc.methodNames());
            });
        });

        // Add the basics
        /*io_router.on("*", function(sock, args, next){
            //console.log("*: ", arguments);
            var name = args[0], msg = args[1];
            obj.info("BIRD3 Events (Socket.IO): "+name+"("+msg+")");
            return next();
        });*/
    }

    debug("BIRD3 Events: Initializing...");

    // Add event emitter
    obj.on = evt.on;
    obj.emit = evt.emit;
    obj.once = evt.once;

    if(typeof io == "undefined" || io == null) {
        // Mixed-matter
        obj.onIO = dont("onIO");
        obj.emitIO = dont("emitIO");
        obj.rpc = {};
        obj.rpc.addSync = dont("addSync");
        obj.rpc.addAsync = dont("addAsync");
    } else {
        makeIo();
    }
    if(typeof redis == "undefined" || redis == null) {
        obj.onRedis = dont("onRedis");
        obj.emitRedis = dont("onRedis");
    }

    obj.onAll = function(name, cb) {
        if(typeof io != "undefined" && io != null) {
            /*io_router.on("*", function(sock, args, next){
                var name = args.shift(), msg = args.shift();
                cb(name, msg);
            });*/
        }
        if(typeof redis != "undefined" && redis != null) {
            subscriber.on("message", function(ch, msg){
                var o = JSON.parse(msg);
                cb(o.name, o.data);
            });
            this.on(name, cb);
        }
    };
    obj.emitAll = function(name, data) {
        if(typeof redis != "undefined" && redis != null)
            this.emitRedis(name, data);

        if(typeof io != "undefined" && io != null)
            this.emitIO(name, data);

        this.emit(name, data);
    };

    process.on('uncaughtException', function(err){
        obj.emit("error", err);
    });

    return obj;
}
