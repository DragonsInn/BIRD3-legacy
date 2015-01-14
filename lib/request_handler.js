// Core
var execSync = require("exec-sync"),
    fs = require("fs"),
    url = require("url"),
    path = require("path"),
    crypto = require("crypto"),
    zlib = require("zlib"),
    portscanner = require('portscanner'),
    mime = require("mime");

// Connect
var connect = require("connect"),
    php = require("connect-yii"),
    vhost = require("vhost"),
    versions = require("versions"),
    srvStatic = require("connect-static"),
    cachify = require("connect-cachify-static"),
    expiry = require("static-expiry"),
    cookieParser = require("cookie-parser"),
    session = require("express-session"),
    RedisStore = require('connect-redis')(session),
    oj = require("connect-oj");

// Servers
var BIRDmain = connect(),
    CDN = connect();

module.exports = function(app, httpServer) {
    // We need to incororate with NGINX or any other webserver here.
    log.info("BIRD3 WebService: Starting...");

    // Sub handlers.
    //require("./api_handler.js")(BIRDmain);
    require("./cloudflare_worker.js")(CDN);

    function fallbackHandler(req, res, next){
        // Preps
        var rqFile = config.base+req.originalUrl;
        var mt = mime.types[path.extname(rqFile).substr(1)];
        var rqObj = {"Content-type":mt};
        log.info("Static fallback: "+rqFile+" | "+mt);
        fs.stat(rqFile, function(err,stats){
            if(err) { return next(); }
            if(stats.isSymbolicLink()) {
                fs.readlink(rqFile, function(err, linkstr){
                    if(err) { log.error(err); next(); }
                    log.info("--> Redirecting request \""+req.url+"\" to \""+linkstr+"\"");
                    var baseDir = path.dirname(rqFile);
                    var realFile = path.join(baseDir, linkstr);
                    fs.readFile(realFile, function(err, data){
                        if(err) { log.error(err); next(); }
                        res.writeHead(200,rqObj);
                        res.end(data);
                    });
                });
            } else {
                fs.readFile(rqFile, function(err, data){
                    if(err) { log.error(err); next(); }
                    res.writeHead(200,rqObj);
                    res.end(data);
                });
            }
        });
    }

    // Initialize the cache server...
    if(config.CDN.enable == "yes") {
        var __url = 'https://'+config.CDN.url+':'+config.CDN.port+'/';
        log.info("BIRD3 WebCache -> Caching : External ( "+__url+" )");
        // Configure versions...
        versions.set('blacklisted extensions', ['.conf', '.log', '.gz', '.json', '.pid']);
        versions.set('root', config.base);
        versions.set('directory', './cdn');
        versions.set('ignore querystring', false);
        versions.set('expire internal cache', '2 days');
        versions.set('version', config.version);
        versions.set('plugins', [{ name: 'logger', config: 'short' }, 'logger']);
        versions.listen(config.CDN.port);
        // And our good old HTTP service.
    } else {
        var __url = 'http://'+config.BIRD3.url+'/cdn';
        log.info("BIRD3 WebCache -> Caching : Internal ( "+__url+" )");
        app.use(vhost(config.BIRD3.url, CDN));
        // OJ is sort-of dynamic, so it needs to be first.
        var files={};
        CDN.use("/cdn/oj", function(req, res, next){
            var file = config.base+"/cdn/oj"+req.url;
            if(fs.existsSync(file)) {
                var age = 30*24*60*60;
                var out;
                var time = Date.now();
                var d = new Date(Date.now() + age*1000);
                if(typeof files[file] != "undefined" && files[file].time < time) {
                    out = files[file].out;
                    files[file].time = time;
                } else {
                    var buf = fs.readFileSync(file);
                    out = crypto.createHash('md5').update(buf).digest('hex');
                    files[file] = {};
                    files[file].out = out;
                    files[file].time = time;
                }
                if(
                    typeof req.headers["if-none-match"] != "undefined"
                    && req.headers["if-none-match"] == out
                ) {
                    res.writeHead(304);
                    res.end();
                    return;
                } else {
                    res.setHeader("Etag", out);
                    res.setHeader("Cache-control", "public, must-revalidate, max-age="+age);
                    res.setHeader("Expires", d.toUTCString());
                    return next();
                }
            } else return next();
        });
        CDN.use("/cdn/oj", oj({
            dir: config.base+"/cdn/oj"
        }));
        // For any generic file, this will work.
        CDN.use("/cdn", cachify(config.base+"/cdn", {maxCacheSize: 20971520}));
        srvStatic({
            dir: config.base+"/cdn",
            aliases: [ ["/","/index.html"] ],
            followSymlinks: true,
            ignoreFile: function(fullPath) {
                var basename = path.basename(fullPath);
                return /^\./.test(basename)
                    || /~$/.test(basename)
                    || /^(.+\.php)$/.test(basename);
            }
        }, function(err, middleware) {
            if (err) throw err;
            CDN.use(config.CDN.baseUrl, middleware);
        });
    }

    app.use(vhost(config.BIRD3.url, BIRDmain));
    // Configuring the BIRD Main server.
    BIRDmain.use(cookieParser());
    /*BIRDmain.use(session({
        store: new RedisStore({
            prefix: "BIRD3.Session.",
            db: 0
        }),
        name: 'PHPSESSID',
        secret: config.version
    }));*/
    BIRDmain.use(php({
        root: config.base,
        index: "app.php",
        serverName: config.BIRD3.url,
        serverHost: config.BIRD3.host,
        serverPort: config.BIRD3.http_port
    })/*, function(req,res){
        console.log("o.o");
    }*/);
    BIRDmain.use(fallbackHandler);


    log.info("BIRD3 WebService: Running.");
}
