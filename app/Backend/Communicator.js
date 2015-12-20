//var io_router = require('socket.io-events')();
var events = require("events").EventEmitter;
var avs = require("avs-rpc");
var util = require("util");
var merge = require("merge");
var BIRD3 = require("BIRD3/Support/GlobalConfig");
var log = BIRD3.log.makeGroup("Communicator");

function dont(m) {
    return function() {
        throw new Error("You may not call "+m+" at this point!");
    }
}

function Communicator(io, redis) {
    if(!(this instanceof Communicator)) {
        return new Communicator(io, redis);
    }

    // Keep a ref to self.
    var self = this;

    // The channel for Redis and SC
    var channel;

    // ... extends EventEmitter
    events.call(this);

    function onRedisError(e) {
        log.error("Redis error: "+e);
    }

    function makeRedis(self) {
        self.channel    = channel = "BIRD3";
        var subscriber = redis.createClient();
        var publisher  = redis.createClient();
        subscriber.on("subscribe", function(ch, c){
            log.info("BIRD3 has joined Redis channel: "+ch);
        }).on("error", onRedisError);
        subscriber.subscribe(channel);
        self.onRedis = function(name, cb) {
            subscriber.on("message", function(ch, msg){
                if(ch!=channel) return;
                var o = JSON.parse(msg);
                if(o.name == name) cb(o.data);
            });
        };
        self.emitRedis = function(name, data) {
            publisher.publish(channel, JSON.stringify({
                name: name,
                data: data
            }));
        };
        self.redis = {
            subscriber: subscriber,
            publisher: publisher
        };
    }

    function makeIo(self) {
        // Mixed-matter
        self.onIO = io.on;
        self.emitIO = io.emit;

        // Make it public:
        self.io = io;

        // Private members
        var sync_methods = {},
            async_methods = {};
        self.rpc = {
            // Public functions
            addSync: function(name, cb) {
                sync_methods[name]=cb;
                io.emit("rpc_init_data", self.rpc.methodNames());
            },

            addAsync: function(name, cb) {
                async_methods[name]=cb;
                io.emit("rpc_init_data", self.rpc.methodNames());
            },

            methodNames: function() {
                return Object.keys(merge(sync_methods, async_methods));
            },

            implementTo: function(rpcObj) {
                rpcObj.implement(sync_methods);
                rpcObj.implementAsync(async_methods);
            }
        };
        io.on("connection", function(sock){
            var rpc = new avs.scRpc(sock);
            self.rpc.implementTo(rpc);
            sock.on("rpc_init", function(){
                sock.emit("rpc_init_data", self.rpc.methodNames());
            });
        });
    }

    log.debug("BIRD3 Events: Initializing...");

    if(typeof io == "undefined" || io == null) {
        // Mixed-matter
        this.onIO = dont("onIO");
        this.emitIO = dont("emitIO");
        this.rpc = {};
        this.rpc.addSync = dont("addSync");
        this.rpc.addAsync = dont("addAsync");
    } else {
        makeIo(this);
    }

    if(typeof redis == "undefined" || redis == null) {
        this.onRedis = dont("onRedis");
        this.emitRedis = dont("onRedis");
    } else {
        makeRedis(this);
    }

    this.onAll = function(name, cb) {
        // SocketCluster
        if(typeof io != "undefined" && io != null) {
            /*io_router.on("*", function(sock, args, next){
                var name = args.shift(), msg = args.shift();
                cb(name, msg);
            });*/
        }

        // Redis
        if(typeof redis != "undefined" && redis != null) {
            subscriber.on("message", function(ch, msg){
                var o = JSON.parse(msg);
                cb(o.name, o.data);
            });
        }

        // EventEmitter
        this.on(name, cb);
    };

    this.emitAll = function(name, data) {
        if(typeof io != "undefined" && io != null)
            this.emitIO(name, data);

        // Redis
        if(typeof redis != "undefined" && redis != null)
            this.emitRedis(name, data);

        // EventEmitter
        this.emit(name, data);
    };

    // Emergency handler
    process.on('uncaughtException', function(err){
        this.emit("error", err);
    }.bind(this));
}

util.inherits(Communicator, events);
module.exports = Communicator;
