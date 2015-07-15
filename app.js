// Just a small thing to do.
Error.stackTraceLimit = Infinity;

// Misc
var args = require("optimist").argv,
    me = require("package")(__dirname),
    redis = require("redis"),
    getports = require("getports"),
    async = require("async"),
    child_process = require("child_process");

// Clustering
var PowerHouse = require('powerhouse'),
    cluster = require("cluster"),
    cpus = require("os").cpus().length;

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
            config: global.config,
            reloadable: false
        },{
            title: "BIRD3: Front-End",
            //exec: "./node-lib/frontent_worker.js",
            exec: "./node-lib/socketcluster_worker.js",
            type: "child",
            config: global.config,
            //amount: config.maxWorkers,
            reloadable: false
        },{
            title: "BIRD3: WebPack",
            exec: "./node-lib/webpack_worker.js",
            type: "cluster",
            config: global.config.wpKey,
            reloadable: true
        }
    ],
    master: function(conf, run) {
        async.parallel({
            mysql: function(cb) {
                log.info("Testing MySQL...");
                var conn = require("mysql").createConnection({
                    host:       "localhost",
                    user:       config.DB.user,
                    password:   config.DB.pass,
                    database:   config.DB.mydb
                });
                conn.connect(function(err){
                    if(err) {
                        log.error("MySQL failed.");
                        cb(err);
                    } else {
                        log.info("MySQL works.");
                        cb();
                    }
                });
            },
            redis: function(cb) {
                log.info("Testing Redis...");
                var client = redis.createClient();
                client.on("ready", function(){
                    log.info("Redis works.");
                    cb(null, client);
                });
                client.on("error", function(err){
                    log.error("Redis failed.");
                    cb(err);
                });
            },
            php: function(cb) {
                log.info("Testing PHP...");
                child_process.exec("php -m", function(error, stdout, stderr){
                    if(error) {
                        log.error("PHP failed.");
                        return cb(error);
                    }
                    var modules = require("ini").parse(stdout);
                    var pm = modules["PHP Modules"];
                    var reqs = [
                        // hprose
                        "hprose","sockets",
                        // For workerman
                        "sysvmsg","sysvsem","sysvshm","pcntl",
                        // BIRD3
                        "mysql","runkit","redis","PDO",
                    ];
                    var is_working = false;
                    reqs.forEach(function(v,i){
                        if(!pm[v]) {
                            log.error("PHP failed.");
                            is_working = false;
                            return cb(new Error("PHP extension '"+v+"' not found."));
                        } else is_working = true;
                    });
                    if(is_working) {
                        log.info("PHP works.");
                        return cb();
                    }
                });
            }
        }, function(err, res){
            if(err) {
                log.error(err);
                process.exit(1);
            } else {
                global.BIRD3 = require("./node-lib/communicator.js")(null, redis);
                require("./node-lib/error_handler.js")();
                mylog.info("Starting: BIRD@"+config.version);
                var sub = redis.createClient();
                var redisClient = res.redis;
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
                        if(o.name=="rpc.log") {
                            try {
                                mylog[o.data.method].apply(mylog, o.data.args);
                            } catch(e) {
                                mylog.error("Unhandled call: %s",JSON.stringify(o));
                            }
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
