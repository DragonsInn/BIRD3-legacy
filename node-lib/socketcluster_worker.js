Error.stackTraceLimit = Infinity;

var express = require("express"),
    house = require("powerhouse")(),
    SocketCluster = require('socketcluster').SocketCluster;

(function FrontentWorker(conf) {
    global.config = global.config || conf;

    var socketCluster = new SocketCluster({
        workers: config.maxWorkers || 4,
        stores: 1,
        port: config.BIRD3.http_port,
        host: config.BIRD3.host,
        appName: config.app.name,
        workerController: require.resolve("./socketcluster/worker"),
        storeController: require.resolve("./socketcluster/store"),
        storeOptions: {
            host: '127.0.0.1',
            port: 6379
        },
        socketChannelLimit: 100,
        rebootWorkerOnCrash: config.debug || false
    });
    house.addShutdownHandler(function(ctx, next){
        socketCluster.killWorkers();
        socketCluster.killBrokers();
        next();
    });
})(JSON.parse(process.env.POWERHOUSE_CONFIG).config);
