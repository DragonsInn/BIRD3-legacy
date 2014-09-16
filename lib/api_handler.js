var glob = require("glob").sync,
    path = require("path");

module.exports = function(app) {
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

    var handler = function(req, res, next) {
        console.log("-- "+req.url);
        var parts = req.url.match(/\/(.+?)\/(.+?)\/(.+)/);
        console.log(parts);
        var section = parts[1];
        var fnc = parts[2];
        var data = parts[3] || null;

        log.info("API request: "+section+"::"+fnc+(data==null?"":"("+data+")"));

        // Sanity check
        if(typeof api[section] != "undefined" && typeof api[section][fnc] == "function") {
            res.writeHead(200, {'Content-Type': 'text/plain'});
            var out = JSON.stringify( api[section][fnc](data) );
            res.end(out);
        } else next();
    }

    app.use("/api", handler);
}
