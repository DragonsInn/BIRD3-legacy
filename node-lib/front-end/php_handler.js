var sh = require('shelljs');
var spawn = require("child_process").spawn;
var hprose = require("hprose");
var fs = require("fs");
var path = require("path");
var url = require("url");
var util = require("util");
var redis = require("redis").createClient();
var async = require("async");
var ginga = require("ginga");
var ware = ginga();
var hprosePort = null;

module.exports = function() {
    // Define the middleware stuff
    var defs = {
        pre: function(ctx,next){
            argo = ctx.args[0];
            ["req","res","opt","arg","php"].forEach(function(v,k){
                if(typeof argo[v] != "undefined")
                    ctx[v] = argo[v];
            });
            return next();
        },
        invok: function(ctx,done){
            return done(null, ctx);
        }
    };
    ware.define("preprocess", defs.pre, defs.invok);
    ware.define("postprocess",defs.pre, defs.invok);
    ware.define(
        "request",
        function(ctx, next, terminate) {
            var req = ctx.req = ctx.args[0].req;
            var res = ctx.res = ctx.args[0].res;

            async.series([
                function(step) {
                    if(hprosePort == null) {
                        redis.get("bird3.hprosePort", function(err, portnr){
                            if(err) return step(err);
                            hprosePort=Number(portnr);
                            step();
                        });
                    } else step();
                },
                function(step) {
                    var client = ctx.client = new HproseTcpClient("tcp://127.0.0.1:"+hprosePort);
                    client.on("error", step);
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
                                REQUEST_METHOD: req.method,
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
                            _POST: (function(){
                                if(req.method != "POST") return {};
                                if(typeof req.body == "object" && !Buffer.isBuffer(req.body)) {
                                    return req.body
                                } else {
                                    return {};
                                }
                            })(),
                            _FILES: {}, // Handled in preprocessor
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
                        var ct;
                        if(typeof v.type != "undefined") {
                            ct = (new Buffer(v.data)).toString("utf8");
                        } else if(Buffer.isBuffer(v)) {
                            ct = v.toString("utf8");
                        } else {
                            ct = v;
                        }
                        arg.request._COOKIE[k]=ct;
                    }

                    // We _need_ webpack.
                    redis.get("BIRD3.webpack",function(err,chunk){
                        if(err) return step(err);
                        opt.userData.webpackHash = chunk;

                        // All data collected. NEXT.
                        ctx.opt = opt;
                        ctx.arg = arg;
                        step();
                    });
                }
            ], function(err, rt){
                if(err) return terminate(err);
                else return next();
            });
        },
        function(ctx, done) {
            var req = ctx.req;
            var res = ctx.res;
            ware.preprocess(ctx,function(pp_err, pp_res){
                if(pp_err) return done(pp_err);
                ctx.client.invoke("yii_run", [ctx.arg, ctx.opt], function(obj){
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
                    ctx.php = obj;
                    ware.postprocess(ctx,function(ps_err,ps_res){
                        if(ps_err) {
                            return done(ps_err);
                        } else {
                            res.end(obj.body);
                            return done();
                        }
                    });
                }, function() {
                    console.log(arguments);
                    res.end("Error");
                    done();
                });
            });
        }
    );

    this.use = function(){ return ware.use.apply(ware,arguments); };
    this.middleware = function PHPMiddleware(req,res,next) {
        ware.request({
            req: req,
            res: res
        },function(err,res){
            if(err) return next(err);
        });
    };

    return this;
}
