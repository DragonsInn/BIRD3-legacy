import SCWorker from "socketcluster/scworker";
import path from "path";
import findRoot from "find-root";

// Express and middlewares
import express from "express";
import serveStatic from "serve-static";
import morgan from "morgan";
import healthChecker from "sc-framework-health-check";
import compression from "compression";
import multiparty from "connect-multiparty";
import redisMiddleware from "connect-redis";
import cookieParser from "cookie-parser"

// Modules
import registerHome from "BIRD3/App/Home";

class Worker extends SCWorker {
  run() {
    // @FIXME Use RPC log
    console.log('   >> Worker PID:', process.pid);

    var environment = this.options.environment;
    var httpServer = this.httpServer;
    var scServer = this.scServer;

    var app = express();

    // Middlewares

    // Request logging
    // @FIXME use RPC logging
    if (environment === 'dev') { app.use(morgan('dev')); }

    // Serve static files
    // @TODO Should this be at the top, or the bottom of the routing chain?
    app.use(serveStatic(path.join(findRoot(), "data/bird3/public")));

    // Add GET /health-check express route
    healthChecker.attach(this, app);

    httpServer.on('request', app);

    var count = 0;

    /*
      In here we handle our incoming realtime connections and listen for events.
    */
    scServer.on('connection', function (socket) {

      // Some sample logic to show how to handle client events,
      // replace this with your own logic

      socket.on('sampleClientEvent', function (data) {
        count++;
        console.log('Handled sampleClientEvent', data);
        scServer.exchange.publish('sample', count);
      });

      var interval = setInterval(function () {
        socket.emit('random', {
          number: Math.floor(Math.random() * 5)
        });
      }, 1000);

      socket.on('disconnect', function () {
        clearInterval(interval);
      });
    });
  }
}

new Worker();
