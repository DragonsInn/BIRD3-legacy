var http = require("http"),
    router = require("router")(),
    httpProxy = require("weo-http-proxy"),
    server = http.createServer(router).listen(8080);

var proxy = new httpProxy.RoutingProxy();

router.all("/", function(req, res){
    res.writeHead(200);
    res.end('hello index page');
});

router.all("*", function(req, res){
    req.url = req.params.wildcard;
    req.headers.host = "dragonsinn.tk:80";
	
	console.log("Now loading: "+req.url);

    proxy.proxyRequest(req, res, {
      host: 'dragonsinn.tk',
      port: 80
    });
});
