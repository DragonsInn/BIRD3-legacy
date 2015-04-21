var sh = require('shelljs');
var spawn = require("child_process").spawn;
var hprose = require("hprose");
var fs = require("fs");
var path = require("path");
var url = require("url");
var util = require("util");

module.exports = function() {
    return function(req,res,next){
        var client = new HproseTcpClient("tcp://127.0.0.1:"+config.hprosePort);
        client.on("error", function(e){
            BIRD3.error("Error in hprose client:", require("util").inspect(e));
            //res.status(500).end("Internal error");
        });
        // Vars
        var rfile = path.join(config.base,req.url);
        var file = (
            req.url!="/" && fs.existsSync(rfile)
            ? rfile.replace(config.base, "")
            : "/app.php"
        );

        var arg = {
            request: {
                _SERVER: {
                    QUERY_STRING: url.parse(req.url).query,
                    PHP_SELF: req.url,
                    SCRIPT_FILENAME: file,
                    SCRIPT_NAME: file,
                    REQUEST_URI: req.url,
                    DOCUMENT_URI: file,
                    DOCUMENT_ROOT: config.base,
                    REMOTE_ADDR: req.ip,
                    REMOTE_PORT: req.connection.remotePort,
                    SERVER_ADDR: config.BIRD3.host,
                    SERVER_PORT: config.BIRD3.http_port,
                    SERVER_NAME: config.BIRD3.url,
                    REDIRECT_STATUS: res.statusCode,
                    SERVER_PROTOCOL: "HTTP/"+req.httpVersion,
                    GATEWAY_INTERFACE: "bird3-hprose/1.0",
                    SERVER_SOFTWARE: "BIRD@"+config.version
                },
                _COOKIE: {},
                _GET: req.query,
                _POST: (req.method=="POST"?req.body:{}),
                _FILES: (req.method=="POST"?req.files:{}),
                _REQUEST: {}
            },
            headers: req.headers
        };
        var opt = {
            userData: {},
            config: config
        };

        // Adding up...
        for(var k in req.headers) arg.request._SERVER["HTTP_"+k.toUpperCase().replace("-","_")]=req.headers[k];
        for(var k in req.query) arg.request._REQUEST[k]=req.query[k];
        for(var k in req.cookies) {
            var v = req.cookies[k];
            arg.request._COOKIE[k]=(Buffer.isBuffer(v) ? v.toString("utf8") : v);
        }
        if(req.method=="POST") {
            for(var k in req.body) arg.request._REQUEST[k]=req.body[v];
        }

        // Let something happen to this data...but how? o.o
        // I'll find a way. Somehow. So, FIXME soon.
        BIRD3.emit("php_request", {
            userData: opt.userData,
            ctx: arg
        });

        client.invoke("yii_run", arg, opt, function(obj){
            var status = obj.status || 200;
            res.status(status);
            for(var k in obj.headers) {
                var v = obj.headers[k];
                res.setHeader(k,v);
            }
            for(var k in obj.cookies) {
                var v = obj.cookies[k];
                res.cookie(k, v[0], v.opts);
            }
            res.end(obj.body);
        });
    }
}
