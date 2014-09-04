var glob = require("glob").sync,
    path = require("path");

module.exports = function(router) {
    // This function applies for any /api/{section}/{function}/*
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

    var handler = function(req, res) {
        var section = req.params.section;
        var fnc = req.params.function;
        var data = req.params.wildcard || null;

        log.info("API request: "+section+"::"+fnc+(data==null?"":"("+data+")"));

        // Sanity check
        if(typeof api[section] != "undefined" && typeof api[section][fnc] == "function") {
            res.writeHead(200, {'Content-Type': 'text/plain'});
            var out = JSON.stringify( api[section][fnc](data) );
            res.end(out);
        }
    }

    router.all("/api/{section}/{function}/*", handler);
    router.all("/api/{section}/{function}",   handler);
}
