// Just a small thing to do.
Error.stackTraceLimit = Infinity;

// Misc
var args = require("optimist").argv,
    me = require("package")(__dirname),
    redis = require("redis"),
    getports = require("getports");

// Clustering
var PowerHouse = require('powerhouse'),
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
// Max workers
config.maxWorkers = args.workers || cpus;
// The key to share WebPack data on
config.wpKey = "BIRD3.webpack";

// Logging
global.log = process.logger = mylog = require("./node-lib/logger.js")(config.base);
process.logger.crit = process.logger.error;
process.logger.notice = process.logger.info;

var house = PowerHouse({
    title: "BIRD3: Main",
    // All other processes likely only need one but Http will want more
    amount: 1,
    //shutdownTimout: 10000, // FIXME: powerhouse doesnt have this yet
    workers: [
        {
            title: "BIRD3: Hprose+Workerman",
            exec: "./node-lib/workerman_worker.js",
            amount: 1,
            config: global.config
        },{
            title: "BIRD3: Front-End",
            //exec: "./node-lib/frontent_worker.js",
            exec: "./node-lib/socketcluster_worker.js",
            type: "child",
            config: global.config,
            //amount: config.maxWorkers
        },{
            title: "BIRD3: WebPack",
            exec: "./node-lib/webpack_worker.js",
            type: "cluster",
            config: global.config.wpKey
        }
    ],
    master: function(conf, run) {
        global.BIRD3 = require("./node-lib/communicator.js")(null, redis);
        require("./node-lib/error_handler.js")();
        mylog.info("Starting: BIRD@"+config.version);
        var sub = redis.createClient();
        var redisClient = redis.createClient();
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
            global.config.hprosePort = ports[0];
            redisClient.set("bird3.hprosePort", ports[0]);
            run();
        });
    }
});

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
