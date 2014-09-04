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
    path = require("path"),
    portscanner = require('portscanner');

module.exports = function(http) {
    // We need to incororate with NGINX or any other webserver here.
    log.info("BIRD3 WebService: Starting...");

    // We check for the open port first.
    portscanner.checkPortStatus(config.app_port, config.host, function(err, status){
        if(status=="closed") {
            log.error("BIRD3 WebService: App port is NOT active! Start NGINX or `php -s ...` first.");
        } else {
            // First come the modular stuff...
            require("./api_handler.js")(router);

            router.all("/socket.io/*",function(){
                // Do NOT respond here. This is socket.io property.
            });

            // The catch-all.
            router.all("*", function(req, res){
                log.info("Request: "+req.socket.remoteAddress+" -> "+req.headers.host+" "+req.method+" "+req.params.wildcard);

                req.headers["BIRD3_VERSION"]=config.version;

                if(req.url == "/") req.url = "/app.php";

                var obj = req.params.wildcard;
                var rpath = path.resolve(config.base+"/"+obj);
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

            // Initialize it.
            http.on("request", router);

            log.info("BIRD3 WebService: Running.");
        }
    });
}
