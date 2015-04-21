var glob = require("glob").sync,
    path = require("path"),
    bodyParser = require("body-parser"),
    debug = require("debug")("bird3:api");

module.exports = function(app) {
    debug("BIRD3 APIService: Starting...");

    // There basically isnt anything to go API about.
    // However, we can return the IP as a test.
    debug("BIRD3 APIService: Registering responders...");
    var api={};
    var responder = glob( __dirname + "/api/*.js" );
    for(var i=0; i <= responder.length; i++) {
        if(typeof responder[i] == "undefined") continue;
        debug("BIRD3 APIService -> "+responder[i]);
        api[path.basename(responder[i], ".js")] = require(responder[i]);
    }

    app.use(bodyParser.json());
    app.use("/api", function(req, res, next){
        BIRD3.info("BIRD3 APIService -> "+req.url);
        var apiStr = req.url.substr(1);
        var parts = apiStr.split("/");
        var section, func, data;
        if(typeof parts[0] == "undefined" || req.url == "/") {
            res.writeHead(500, {"Content-type":"text/json"});
            res.end(JSON.stringify({
                error: "Bad API request: Function is undefined!"
            }));
            return;
        }
        if(typeof parts[1] == "undefined") {
            res.writeHead(500, {"Content-type":"text/json"});
            res.end(JSON.stringify({
                error: "Bad API request: Function is undefined!"
            }));
            return;
        }
        section = parts[0];
        func = parts[1];
        // Cut two parts out.
        parts.shift();
        parts.shift();
        // Put it back together into a string.
        data = parts.join("/");
        res.writeHead(200, {"Content-type":"text/json"});
        if(typeof api[section] == "undefined") {
            return res.end(JSON.stringify({
                error: "Bad API call: section unknown."
            }));
        }
        if(typeof api[section][func] == "undefined") {
            return res.end(JSON.stringify({
                error: "Bad API call: Section does not have specified function."
            }));
        }
        return res.end(JSON.stringify(
            api[section][func](data)
        ));
    });

    debug("BIRD3 APIService: Started!");
}
