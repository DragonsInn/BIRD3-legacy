var io_router = require('socket.io-events')();
var events = require("events");
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
    };
    obj.emitAll = function(name, data) {
        obj.emitRedis(name, data);
        obj.emitIO(name, data);
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

    // Add the basics
    io_router.on("*", function(sock, args, next){
        var name = args.shift(), msg = args.shift();
        log.info("BIRD3 Events (Socket.IO): "+name+"("+JSON.stringify(msg)+")");
    });
    subscriber.on("message", function(ch, msg){
        log.info("BIRD3 Events (Redis"+ch+"): "+JSON.stringify(msg));
    });

    return obj;
}
