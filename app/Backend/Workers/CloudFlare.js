var ccf = (config.API.cloudflare.enable == "yes"
    ? require("cloudflare").createClient(config.API.cloudflare)
    : null
);
var ncf = require("node_cloudflare");
var debug = require("debug")("bird3:cloudflare");

module.exports = function(app) {
    // This is the local app instance, invoked before vhost.
    debug("BIRD3 CloudFlare Worker: Starting...");

    if(ccf!=null) {
        debug("BIRD3 CloudFlare Worker: CF client enabled!");
    }

    ncf.load(function(error, fs_error){
        if(error){
            throw error;
        }
        if(fs_error){
            throw fs_error;
        }
    });

    app.use(function(req,res,next){
        if(ncf.check(req)) {
            debug("BIRD3 CloudFlare Worker -> "+ncf.get(req));
        }
        next();
    });
}
