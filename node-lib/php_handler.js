var sh = require('shelljs');
var spawn = require("child_process").spawn;
var hprose = require("hprose");
JSON.stringifySafe = require("json-stringify-safe");
var freeport = require("freeport");

// Try to find PHP...
if(!sh.which("php")) {
    log.error("You need PHP installed!");
    process.exit(1);
} else {
    module.exports = function(app) {
        var args = [
            config.base+"/php-lib/request_handler.php",
            "start",
            "--host=127.0.0.1",
            "--port=9999",
            "--workers=1"
        ];
        var opts = {
            cwd: config.base,
            env: process.env,
            stdio: ["ignore", process.stdout, process.stderr]
        };
        var php = spawn("php", args, opts);
        /*php.stderr.on("data", function(ch){
            var obj = JSON.parse(ch.toString("utf8"));
            log[obj.method].apply(log, obj.args);
        });*/
        php.on("exit", function(e){
            BIRD3.emit("error","PHP exited: "+e);
        });

        app.use("/", function(req,res,next){
            var client = new HproseTcpClient("tcp://127.0.0.1:9999");
            client.on("error", function(e){
                console.log("Error in hprose client:", require("util").inspect(e));
                client.invoke("yii_stop");
                //res.status(500).end("Internal error");
            });
            // Serialize stuff for the client
            var j_req = JSON.stringifySafe(req);
            var j_res = JSON.stringifySafe(res);
            var opts = {
                _SERVER: {
                    REMOTE_ADDR: req.ip,
                    DOCUMENT_ROOT: config.base,
                    HTTP_HOST: config.BIRD3.url+":"+config.BIRD3.http_port,
                    QUERY_STRING: "",
                    SERVER_PORT: config.BIRD3.http_port
                },
                config: config
            };
            client.invoke("yii_run", j_req, j_res, opts, function(obj){
                if(obj.killme) client.invoke("yii_stop");

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
        });
    }
}
