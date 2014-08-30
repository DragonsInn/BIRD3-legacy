var router = require("router")(),
    httpProxy = require("weo-http-proxy"),
    proxy = new httpProxy.RoutingProxy(),
    pOpt = {
        host: config.host,
        port: config.app_port // The dragon's inn port. wut!
    },
    execSync = require("exec-sync");

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
    req.url = req.params.wildcard;
    req.headers.host = "dragonsinn.tk:80";
    proxy.proxyRequest(req, res, pOpt);
});

module.exports = router;

log.info("BIRD3 WebService: Running.");
