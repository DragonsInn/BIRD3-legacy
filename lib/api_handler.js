var glob = require("glob").sync,
    path = require("path"),
    rest = require("connect-rest"),
    bodyParser = require("body-parser");

module.exports = function(app) {
    log.info("BIRD3 APIService: Starting...");

    // There basically isnt anything to go API about.
    // However, we can return the IP as a test.
    log.info("BIRD3 APIService: Registering responders...");
    var api={};
    var responder = glob( __dirname + "/api/*.js" );
    for(var i=0; i <= responder.length; i++) {
        if(typeof responder[i] == "undefined") continue;
        log.info("BIRD3 APIService -> "+responder[i]);
        api[path.basename(responder[i], ".js")] = require(responder[i]);
    }

    var handler = function(req, content, cb) {
        var section = req.parameters.section;
        var fnc = req.parameters.fnc;
        var data = req.parameters.data || null;

        log.info("API request: "+section+"::"+fnc+(data==null?"":"("+data+")"));

        // Sanity check
        if(typeof api[section] != "undefined" && typeof api[section][fnc] == "function") {
            return cb(null, JSON.stringify( api[section][fnc](data) ));
        } else return false;
    }

    rest.get({
        path:"/:section/:fnc/*data",
        unprotected: true
    }, handler);

    rest.get({path:"/a",unprotected:true},function(req,ct,cb){
        return cb(null, "oi");
    });

    app.use(bodyParser.urlencoded({ extended: true }));
    app.use(bodyParser.json());
    app.use(rest.rester({
        context: "/api",
        monitoring: {
            populateInterval: 12000*2,
            console: true,
            listener: log.info
        }
    }));

    log.info("BIRD3 APIService: Started!");
}
