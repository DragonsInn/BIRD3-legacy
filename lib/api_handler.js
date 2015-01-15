var glob = require("glob").sync,
    path = require("path"),
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

    app.use(bodyParser.urlencoded({ extended: true }));
    app.use(bodyParser.json());
    app.use("/api", function(req, res, next){
        log.info("BIRD3 APIService -> "+req.url);
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

    log.info("BIRD3 APIService: Started!");
}
