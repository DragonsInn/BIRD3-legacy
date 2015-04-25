// Core
var fs = require("fs"),
    url = require("url"),
    path = require("path"),
    crypto = require("crypto"),
    mime = require("mime")
    mkdirp = require("mkdirp")
    md5_file = require("md5-file");

// Connect
var st = require("st"),
    ex_static = require("express-static"),
    oj = require("connect-oj"),
    php = require("./php_handler.js"),
    bodyParser = require('body-parser'),
    multiparty = require("connect-multiparty"),
    responseTime = require("response-time"),
    compression = require("compression");

var debug = require("debug")("bird3:http");

// Options
var stOpts = {
    index: false,
    dot: false,
    gzip: true,
    cache: { // specify cache:false to turn off caching entirely
        fd: {
            max: 1000, // number of fd's to hang on to
            maxAge: 1000*60*60, // amount of ms before fd's expire
        },
        stat: {
          max: 5000, // number of stat objects to hang on to
          maxAge: 1000*60, // number of ms that stats are good for
        },
        content: {
          max: 1024*1024*64, // how much memory to use on caching contents
          maxAge: 1000*60*60, // how long to cache contents for
                              // if `false` does not set cache control headers
          //cacheControl: 'public, max-age=600' // to set an explicit cache-control
                                              // header value
        }
    }
};

module.exports = function(app) {
    // We need to incororate with NGINX or any other webserver here.
    debug("BIRD3 WebService: Starting...");

    // Some tiny middlewares...
    app.use(compression());
    app.use(responseTime());

    // Expires: header
    app.use(config.CDN.baseUrl, function(req,res,next){
        if(typeof req.query.nocache == "undefined") {
            var now = new Date().getTime();
            var age = stOpts.cache.content.maxAge;
            res.setHeader("Expires", new Date(now+age).toUTCString());
        }
        next();
    });

    // Inject API and CloudFlare
    require("./cloudflare_worker.js")(app);
    require("./api_handler.js")(app);

    // Initialize the cache server...
    var __url = 'http://'+config.CDN.url+config.CDN.baseUrl;
    // CDN must not return caching when not needed.
    app.use(config.CDN.baseUrl, function(req, res, next){
        if("nocache" in req.query) {
            return ex_static(config.base+"/cdn")(req, res, next);
        } else return next();
    });
    // OJ is sort-of dynamic, so it needs to be first.
    var files={};
    app.use("/cdn/oj", function(req, res, next){
        var file = config.base+"/cdn/oj"+req.url;
        if(fs.existsSync(file)) {
            var out;
            var age = 30*24*60*60;
            var time = Date.now();
            var d = new Date(Date.now() + age*1000);
            if(
                typeof files[file] != "undefined" && (
                    files[file].time < time
                    || md5_file(file) == files[file].md5)
            ) {
                out = files[file].out;
                files[file].time = time;
            } else {
                out = md5_file(file);
                files[file] = {};
                files[file].out = out;
                files[file].time = time;
                files[file] = out;
            }
            if(
                "if-none-match" in req.headers
                && req.headers["if-none-match"] == out
            ) {
                res.writeHead(304);
                return res.end();
            } else {
                res.setHeader("Etag", out);
                res.setHeader("Cache-control", "public, max-age="+age);
                res.setHeader("Expires", d.toUTCString());
                return next();
            }
        } else return next();
    });
    app.use("/cdn/oj", oj({
        dir: config.base+"/cdn/oj"
    }));

    var cdn_st = stOpts;
    cdn_st.path = config.base+"/cdn";
    app.use(config.CDN.baseUrl, st(cdn_st));

    // Configuring the BIRD Main server.
    app.use("/", bodyParser.urlencoded({
      extended: true
    }));
    app.use("/", multiparty(config.version));
    app.use("/", php());

    debug("BIRD3 WebService: Running.");
}
