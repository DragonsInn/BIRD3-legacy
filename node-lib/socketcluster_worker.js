Error.stackTraceLimit = Infinity;

var express = require("express"),
    house = require("powerhouse")(),
    SocketCluster = require('socketcluster').SocketCluster;

(function FrontentWorker(conf) {
    global.config = global.config || conf;

    var socketCluster = new SocketCluster({
        workers: config.maxWorkers || 2,
        stores: 1,
        port: config.BIRD3.http_port,
        host: config.BIRD3.host,
        appName: config.app.name,
        workerController: require.resolve("./socketcluster/worker"),
        storeController: require.resolve("./socketcluster/store"),
        socketChannelLimit: 100,
        rebootWorkerOnCrash: config.debug || false
    });
})(JSON.parse(process.env.POWERHOUSE_CONFIG).config);
