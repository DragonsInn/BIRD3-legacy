var router = require("router")(),
    httpProxy = require("weo-http-proxy"),
    proxy = new httpProxy.RoutingProxy(),
    pOpt = {
        host: config.host,
        port: config.app_port // The dragon's inn port. wut!
    },
    execSync = require("exec-sync"),
    fs = require("fs"),
    url = require("url"),
    path = require("path");

// We need to incororate with NGINX or any other webserver here.
// Need to find a way to check for required port...
log.info("BIRD3 WebService: Starting...");

router.all("/", function(req, res){
    req.url = "/app.php";
    req.headers.host = "dragonsinn.tk:80";
    proxy.proxyRequest(req, res, pOpt);
});

router.all("/socket.io/*",function(){
    // Do NOT respond here. This is socket.io property.
});

// The catch-all.
router.all("*", function(req, res){
    log.info("Request: "+req.headers.host+" "+req.method+" "+req.params.wildcard);

    var obj = req.params.wildcard;
    var rpath = path.resolve(config.base+"/"+obj);
    //log.info("CHECKED: "+rpath);
    if(fs.existsSync(rpath)) {
        log.info("Sending proxy request for static file.");
    } else {
        // The URL is something like /site/error - we have to modfiy things.
        if(global.config.yii_fix_routes) {
            // We have to put ?r=controller/action&key=value in ourself.
            var route = req.url.substr(1);
            req.url = "/app.php?r="+route;
        } else {
            req.url="/app.php"+obj;
        }
        log.info("New request URL: "+req.url);
    }
    proxy.proxyRequest(req, res, pOpt);
});

module.exports = router;

log.info("BIRD3 WebService: Running.");
