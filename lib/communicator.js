var redis = require("redis"),
    subscriber = redis.createClient(),
    publisher  = redis.createClient();

module.exports.channel = channel = "BIRD3";

subscriber.subscribe(channel);

module.exports.emit = function(name, data) {
    publisher.publish(channel, JSON.stringify({
        type: name,
        data: data
    }));
}

module.exports.on = function(name, cb) {
    subscriber.on("message", function(ch, msg){
        if(ch==channel) {
            var obj = JSON.parse(msg);
            if(name == obj.type) cb(obj.data);
        }
    });
}

module.exports.onAll = function(cb) {
    subscriber.on("message", function(ch, msg){
        if(ch==channel) {
            var obj = JSON.parse(msg);
            cb(obj.type, obj.data);
        }
    });
}
