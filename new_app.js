// Just a small thing to do.
Error.stackTraceLimit = Infinity;

// Misc
var args = require("optimist").argv,
    me = require("package")(__dirname),
    redis = require("redis"),
    getports = require("getports");

// Clustering
var vc = require('various-cluster'),
    cluster = require("cluster"),
    cpus = require("os").cpus().length,
    sticky = require("sticky-session");

// Initialize the config object.
var ini = require("multilevel-ini"),
    fs = require("fs");
global.config = ini.getSync("./config/BIRD3.ini");
config.base = __dirname;
config.version = me.version;
config.package = me;
config.maxWorkers = args.workers || cpus;

// Logging
global.log = process.logger = mylog = require("./node-lib/logger.js")(config.base);
process.logger.crit = process.logger.error;
process.logger.notice = process.logger.info;

// Tiny RPC stuff
var sub = redis.createClient();
sub.on("error", function(e){
    console.error(e.stack);
    process.exit(1);
});
sub.subscribe("BIRD3");
sub.on("message", function(ch, data){
    if(ch=="BIRD3") {
        var o = JSON.parse(data);
        if(o.name=="bird3.exit") {
            mylog.error("Shutting down the entire server.");
            process.kill(process.pid, "SIGTERM");
        }
    }
});

function startWorkers() {
    // This does not work.
    sticky(1, require("./node-lib/frontent_worker.js"))
        .listen(config.BIRD3.http_port, config.BIRD3.host);

    // Set up our little "clusterfuck" :)
    vc.init({
        title: "BIRD3: Main",
        // All other processes likely only need one but Http will want more
        count: 1,
        shutdownTimout: 10000,
        workers: [
            /*{
                title: "BIRD3: Front-End",
                // The old app.js becomes this
                exec: "node-lib/frontent_worker.js",
                count: config.maxWorkers
            },*/
            {
                title: "BIRD3: Hprose+Workerman",
                exec: "node-lib/workerman_worker.js",
            }
            /*
                Create workers for:
                    - php > Monitor the PHP workerman stuff.
                    - mysql > Clean data, trigger events
                    - backup > schedule a backup here and there
                    - cache > talk to cloudflare and maintain cache
                    - notification > Will send stuff to users and read/drop user updates
                    - chat > will get a LOT of traffic. Do the communication.
                    - chat-ssh > Chat via SSH!
                    - update > look for code updates and make workers restart.
            */
        ]
    });
}

// Upstart logic
if(!cluster.isMaster) {
    getports(6, function(err, ports){
        if(err) {
            mylog.error("Error finding a port: "+err);
            process.exit(1);
        }
        /** All services use Hprose TCP to dish out an internal API
            0: Hprose for Yii
            1: PHP statistics, workerman
            2: Start, stop, query backups
            3: Talk to MySQL
            4: Chat API
            5: trigger update
        */
        mylog.info("Ports to be used: "+JSON.stringify(ports));
        config.hprosePort = ports[0];
        startWorkers();
    });
} else {
    mylog.info("Starting up BIRD@"+config.version);
    // We are a child, no need to grab a port.
    startWorkers();
}
