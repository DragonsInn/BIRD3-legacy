var scRedis = require("sc-redis");
module.exports.run = function (store) {
    process.title = "BIRD3: SC Store";
    scRedis.attach(store);
};
