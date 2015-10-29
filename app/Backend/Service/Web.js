// Core
var fs = require("fs"),
    url = require("url"),
    path = require("path"),
    crypto = require("crypto"),
    mime = require("mime")
    mkdirp = require("mkdirp")
    md5_file = require("md5-file"),
    moment = require("moment");

// Config
var BIRD3 = require("BIRD3/Support/GlobalConfig");

// ExpressJS Web Server
var st = require("st"),
    ex_static = require("express-static"),
    blinker = require("express-blinker"),
    WebDriver = require("BIRD3/Foundation/WebDriver/Dispatcher"),
    bodyParser = require('body-parser'),
    cookies = require("cookie-parser"),
    session = require("express-session"),
    RedisStore = require('connect-redis')(session),
    multiparty = require("connect-multiparty"),
    responseTime = require("response-time"),
    compression = require("compression"),
    favicon = require("serve-favicon"),
    redis = require("redis").createClient();

var debug = require("debug")("bird3:http");

module.exports = function(app, hprosePort) {
    // We need to incororate with NGINX or any other webserver here.
    debug("BIRD3 WebService: Starting...");

    // Some tiny middlewares...
    app.use(compression({
        level: 9,
        memLevel: 9
    }));
    app.use(responseTime());

    // favicon
    app.use(favicon(BIRD3.root+"/cdn/images/favicons/favicon.ico"));

    // Inject API and CloudFlare
    //require("./cloudflare_worker.js")(app);
    //require("./api_handler.js")(app);

    // CDN must not return caching when not needed.
    app.use(BIRD3.config.CDN.baseUrl, function(req, res, next){
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
    app.use(function(req, res, next){
        // A throw-together session implementation.
        var RedisSession = function(rdKey, afterCb){
            var key = this._key = BIRD3.sessionKey + rdKey;
            var self = this;
            redis.get(key, function(err, res){
                if(err) return afterCb(err);
                try{
                    self._store = require("phpjs").unserialize(res);
                } catch(e) {
                    self._store = {};
                }
                afterCb(null, self);
            });
        }
        RedisSession.prototype = {
            _store: {}, _key: null,
            get: function(k) { return this._store[k]; },
            set: function(k,v) { this._store[k]=v; },
            write: function(cb) {
                cb = cb || function(){};
                redis.set(this._key, require("phpjs").serialize(this._store), cb);
            }
        }
        if(typeof req.cookies.PHPSESSID != "undefined") {
            (new RedisSession(req.cookies.PHPSESSID, function(err, sess){
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
            url: BIRD3.config.BIRD3.url
        },
        // Optional data.
        {}
    );
    require("BIRD3/Backend/Handlers/Php")(wd);
    app.use("/", wd.getMiddleware());

    debug("BIRD3 WebService: Running.");
}
