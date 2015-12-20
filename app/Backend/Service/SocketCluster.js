Error.stackTraceLimit = Infinity;

import express from "express";
import {SocketCluster} from "socketcluster";
import BIRD3 from "BIRD3/Support/GlobalConfig";
import path from "path";

var log = BIRD3.log.makeGroup("SC: Master");

export function run(workerConf, house) {
    var config = BIRD3.config;
    var socketCluster = new SocketCluster({
        workers: config.maxWorkers || 4,
        stores: 1,
        port: config.BIRD3.http_port,
        host: config.BIRD3.host,
        appName: config.app.name,
        initController: path.join(BIRD3.root, "app/Backend/SocketCluster/Init"),
        workerController: path.join(BIRD3.root, "app/Backend/SocketCluster/Worker"),
        brokerController: path.join(BIRD3.root, "app/Backend/SocketCluster/Broker"),
        brokerOptions: {
            host: '127.0.0.1',
            port: 6379
        },
        workerOptions: workerConf.config,
        socketChannelLimit: 100,
        rebootWorkerOnCrash: config.debug || false
    });
    house.addShutdownHandler((ctx, next) => {
        if(ctx.event != "exit") return next();
        log.info("Shutting down SocketCluster");
        socketCluster.killWorkers();
        socketCluster.killBrokers();
        next();
    });
}
require("powerhouse")();
