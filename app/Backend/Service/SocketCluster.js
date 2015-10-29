Error.stackTraceLimit = Infinity;

module.exports.run = function(workerConf, house) {
    var express = require("express"),
        SocketCluster = require('socketcluster').SocketCluster,
        BIRD3 = require("BIRD3/Support/GlobalConfig");

    var config = BIRD3.config;
    var socketCluster = new SocketCluster({
        workers: config.maxWorkers || 4,
        stores: 1,
        port: config.BIRD3.http_port,
        host: config.BIRD3.host,
        appName: config.app.name,
        workerController: require.resolve("BIRD3/Backend/SocketCluster/Worker"),
        brokerController: require.resolve("BIRD3/Backend/SocketCluster/Broker"),
        brokerOptions: {
            host: '127.0.0.1',
            port: 6379
        },
        workerOptions: workerConf.config,
        socketChannelLimit: 100,
        rebootWorkerOnCrash: config.debug || false
    });
    house.addShutdownHandler(function(ctx, next){
        if(ctx.event != "exit") return next();
        BIRD3.log.info("Shutting down SocketCluster");
        socketCluster.killWorkers();
        socketCluster.killBrokers();
        next();
    });
}
require("powerhouse")();
