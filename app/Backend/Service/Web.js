// Core
import fs from "fs";
import url from "url";
import path from "path";
import crypto from "crypto";
import mime from "mime";
import mkdirp from "mkdirp";
import md5_file from "md5-file";
import moment from "moment";
import redis from "redis";
import phpjs from "phpjs";

// BIRD3 stuff
import BIRD3 from "BIRD3/Support/GlobalConfig";
import WebDriver from "BIRD3/Foundation/WebDriver/Dispatcher";
import PHPHandler from "BIRD3/Backend/Handlers/Php";

// ExpressJS Web Server
// # Static file server
import st from "st";
import ex_static from "express-static";
import blinker from "express-blinker";
// # Middlewares
import bodyParser from "body-parser";
import cookies from "cookie-parser";
import multiparty from "connect-multiparty";
import responseTime from "response-time";
import compression from "compression";
import favicon from "favicon";
// # Plugins
import session from "express-session";

// var RedisStore = require('connect-redis')(session);

var log = BIRD3.log.makeGroup("Web@"+process.pid);
var debug = () => { log.debug.call(arguments); }

export default (app, hprosePort) => {
    // We need to incororate with NGINX or any other webserver here.
    debug("BIRD3 WebService: Starting...");

    // Some tiny middlewares...
    app.use(compression({
        level: 9,
        memLevel: 9
    }));
    app.use(responseTime());

    // favicon
    //app.use(favicon(BIRD3.root+"/cdn/images/favicons/favicon.ico"));

    // Inject API and CloudFlare
    //require("./cloudflare_worker.js")(app);
    //require("./api_handler.js")(app);

    // CDN must not return caching when not needed.
    app.use(BIRD3.config.CDN.baseUrl, function NoCache(req, res, next){
        if("nocache" in req.query) {
            return ex_static(BIRD3.root+"/cdn")(req, res, next);
        } else return next();
    });
    app.use(BIRD3.config.CDN.baseUrl, blinker(BIRD3.root+"/cdn", [
        {
            test: /\.(jp?g|png|tiff|ttf|eot|woff?|svg|js|css)$/,
            etag: false,
            lastModified: false,
            cacheControl: true,
            cacheKeyword: "public",
            expires: true,
            age: moment.duration(1, "day").asSeconds()
        },{
            test: /.+/,
            etag: false,
            lastModified: false,
            cacheControl: false,
            expires: false,
            age: 0
        }
    ]));

    // Configuring the BIRD Main server.
    app.use("/", bodyParser.urlencoded({
        extended: true
    }));
    app.use("/", bodyParser.raw({
        limit: "50mb"
    }));
    app.use("/", multiparty(BIRD3.package.version));
    app.use("/", cookies());
    app.use(function SessionPickup(req, res, next){
        // A throw-together session implementation.
        var RedisSession = (rdKey, afterCb) => {
            var key = this._key = BIRD3.sessionKey + rdKey;
            var self = this;
            redis.get(key, (err, res) => {
                if(err) return afterCb(err);
                try{
                    self._store = phpjs.unserialize(res);
                } catch(e) {
                    self._store = {};
                }
                afterCb(null, self);
            });
        }
        RedisSession.prototype = {
            _store: {}, _key: null,
            get(k) { return this._store[k]; },
            set(k,v) { this._store[k]=v; },
            write(cb) {
                cb = cb || function(){};
                redis.set(this._key, phpjs.serialize(this._store), cb);
            }
        }
        if(typeof req.cookies.PHPSESSID != "undefined") {
            (new RedisSession(req.cookies.PHPSESSID, (err, sess) => {
                if(err) return next(err);
                req.session = sess;
                next();
            }));
        } else next();
    });

    // Set up the PHP stuff
    var wd = new WebDriver(
        // uri: Host, where hprose is waiting
        "tcp://127.0.0.1:"+hprosePort,
        // requestClass: The class whose ::handle() method we want to call.
        "BIRD3\\App\\Entry\\Server\\Frontend",
        // Host config for HTTP headers and alike
        {
            port: BIRD3.config.BIRD3.http_port,
            url: BIRD3.config.BIRD3.url,
            host: BIRD3.config.BIRD3.host || "localhost"
        },
        // Optional infos
        {
            version: BIRD3.package.version
        }
    );
    PHPHandler(wd);
    app.use("/", wd.getMiddleware());

    debug("BIRD3 WebService: Running.");
}
