// Just a small thing to do.
Error.stackTraceLimit = Infinity;

var hprose = require("hprose");
var ginga = require("ginga");
var url = require("url");
var EE = require("events").EventEmitter;
var util = require("util");

/**
 * @class HproseRequestDispatcher
 *
 * This class exports a middleware, that allows you to send stuff to a Hprose backend.
 * In fact, a hprose backend that serves back Http responses.
 */
function Dispatcher(uri, requestClass, hostConfig, optional) {
    if(!(this instanceof Dispatcher)) {
        return new HproseRequestDispatcher(options);
    }

    // Call the super
    EE.call(this);

    // Self-reference
    var self = this;

    if(!uri) {
        throw new Error("Expected URI, but it was undefined.");
    } else this.uri = uri;

    if(!requestClass) {
        throw new Error("You need to supply a class name to a valid request class.");
    } else this.requestClass = requestClass;

    if(!optional) {
        this.optional = {};
    } else this.optional = optional;

    // Configure host info
    // FIXME: Express should actually know of these...
    var defaultHostConfig = {
        host: "127.0.0.1",
        port: 80,
        url: "localhost"
    };
    ["host","port","url"].forEach(function(propName){
        if(typeof hostConfig[propName] != "undefined") {
            self[propName] = hostConfig[propName];
        } else {
            self[propName] = defaultHostConfig[propName];
        }
    });

    // Define components
    this.hprose = new hprose.client.TcpClient(this.uri);
    this.hprose.on("error", function(e){
        this.emit("error", e);
    }.bind(this));
    var ware = this.ware = ginga();

    // Further define the pre-/postprocess midlewares
    var defs = {
        pre: function(ctx,next){
            ctx.ctx = ctx.args[0];
            return next();
        },
        invok: function(ctx,done){
            return done(null, ctx);
        }
    };
    ware.define("preprocess", defs.pre, defs.invok);
    ware.define("postprocess",defs.pre, defs.invok);

    // Allow customization
    this.use = function(name, cb) {
        this.ware.use(name, cb);
        return this;
    }

    // This function translates a normal request object into something that PHP could understand.
    var transformHeaders = function(req) {
        var rq_url = url.parse(req.url);
        var headers = {
            // The HTTP method
            REQUEST_METHOD: req.method,
            // The query string. In this case: foo=bar&baz=qox
            QUERY_STRING: rq_url.query,
            // The full path to the requested PHP file
            SCRIPT_FILENAME: "/",
            // The url-relative path to the requested PHP file
            SCRIPT_NAME: "/",
            // Holds the URI path and query string. I.e.: /index.php?foo=bar or /foo?herp=derp
            REQUEST_URI: req.url,
            // Equals to SCRIPT_NAME.
            DOCUMENT_URI: req.url,
            // Remote informations
            REMOTE_ADDR: req.ip,
            REMOTE_PORT: req.connection.remotePort,
            // Server informations
            SERVER_ADDR: this.host,
            SERVER_PORT: this.port,
            SERVER_NAME: this.url,
            SERVER_PROTOCOL: "HTTP/"+req.httpVersion,
            GATEWAY_INTERFACE: "nodejs/"+process.version,
            SERVER_SOFTWARE: "nodejs/"+process.version
        };

        for(var name in req.headers) {
            var headerName = "HTTP_"+name.toUpperCase().replace(/\-/g,"_");
            headers[headerName] = req.headers[name];
        }

        return headers;
    }.bind(this);

    var transformCookies = function(req) {
        var cookies = req.cookies;
        for(var name in cookies) {
            var cookie = cookies[name];

            // Do we have to transform it?
            if(Buffer.isBuffer(cookie)) {
                cookie = (new Buffer(cookie)).toString("utf8");
            } else if(typeof cookie == "object") {
                // I need to see cases for this.
                console.log("COOKIE: ",cookie);
            }

            // Done. Put it back into the jar.
            cookies[name] = cookie;
        }
        return cookies;
    }.bind(this);

    this.getMiddleware = function() {
        var self = this;
        return function(req, res, next) {
            // The context we will be referencing forth and back
            var ctx = {
                // This object holds the request information, plus slightly fixed info for PHP.
                request: {
                    url: req.protocol + '://' + req.get('host') + req.originalUrl,
                    query: req.query,
                    postData: req.body,
                    cookies: transformCookies(req),
                    body: req.rawBody,
                    files: req.files || [],
                    server: transformHeaders(req),

                    // Additional request infos
                    method: req.method,
                    headers: req.headers
                },

                // This is an empty object we will be using later.
                response: {},

                // This object is used to let the user supply their own data.
                optional: (typeof self.optional == "function"
                    ? self.optional()
                    : self.optional
                )
            };

            // Invoke the pre-processor.
            ware.preprocess(ctx, function(err, wareCtx){
                var methodName = [requestClass, "handle"].join("_");
                var ctx = JSON.parse(JSON.stringify(wareCtx.ctx));
                // Dullify the context.
                self.hprose.invoke(
                    methodName, [ ctx ],
                    function onHproseRequestSuccess(response) {
                        /*
                            The response object is expected to contain these attibutes:
                                - .status: Integer, the status
                                - .statusText: The status text
                                - .headers: List of headers.
                                - .cookies: List of cookies
                                - .body: The output
                        */
                        ctx.response = response;
                        ware.postprocess(ctx, function(postError, ctx){
                            if(postError) {
                                next(postError);
                            } else {
                                // Set the status
                                res.status(response.status, response.statusText);

                                // Set the headers
                                response.headers.forEach(function(pair){
                                    res.setHeader(pair[0], pair[1]);
                                });

                                // Set cookies
                                response.cookies.forEach(function(cookie){
                                    cookie.options.expires = new Date(cookie.options.expires*1000);
                                    res.cookie(cookie.name, cookie.value, cookie.options);
                                });

                                res.send(response.body);
                            }
                        });
                    },
                    function onHproseRequestError(method, error) {
                        next(error);
                    }
                );
            });
        };
    }
}

// Exporting and stuff
util.inherits(Dispatcher, EE);
module.exports = Dispatcher;
