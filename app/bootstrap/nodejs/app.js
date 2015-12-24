// Just a small thing to do.
Error.stackTraceLimit = Infinity;

// Require BIRD3's config
import BIRD3 from "BIRD3/support/GlobalConfig";

// Misc
import { argv as args } from "optimist";
import redis from "redis";
import getports from "getports";
import async from "async";
import child_process from "child_process";

// Clustering
import PowerHouse from "powerhouse";
import cluster from "cluster";

if(args.workers) {
    BIRD3.maxWorkers = args.workers;
}

// Set this up, so we can reference it later down the code.
BIRD3.ports = {hprose: -1, phpStats: -1, update: -1};

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

// The workers to make.
var workers = [];

// FIXME: Optimally check which workers to run.
// This would help a lot in integration tests.
workers.push({
    title: "BIRD3/Backend: WebDriver",
    exec: require.resolve("BIRD3/Backend/Service/WebDriver"),
    type: "cluster",
    reloadable: false,
    config: BIRD3.ports
});
workers.push({
    title: "BIRD3/Backend: SocketCluster",
    exec: require.resolve("BIRD3/Backend/Service/SocketCluster"),
    type: "child",
    reloadable: false,
    config: BIRD3.ports
});
workers.push({
    title: "BIRD3/Backend: WebPack",
    exec: require.resolve("BIRD3/Backend/Service/WebPack"),
    type: "child",
    reloadable: false
});
workers.push({
    title: "BIRD3/Frontend: CDN",
    exec: require.resolve("BIRD3/Backend/Service/CDN"),
    type: "cluster",
    reloadable: false
});
/*workers.push({
    title: "BIRD3: Misc servers",
    exec: "./node-lib/misc_worker.js",
    type: "cluster",
    reloadable: false
})*/

var house = PowerHouse({
    title: "BIRD3: Main",
    // All other processes likely only need one but Http will want more
    amount: 1,
    //shutdownTimout: 10000, // FIXME: powerhouse doesnt have this yet
    // PowerHouse can now bootstrap itself.
    init: require.resolve("./autoload.js"),
    workers: workers,
    master: function(conf, run) {
        var log = BIRD3.log.makeGroup(false);
        var config = BIRD3.config;
        async.parallel({
            mysql: function(cb) { try{
                var conn = require("mysql").createConnection({
                    host:       "localhost",
                    user:       config.DB.user,
                    password:   config.DB.pass,
                    database:   config.DB.mydb
                });
                conn.connect(function(err){
                    log.info("Testing MySQL...");
                    if(err) {
                        log.error("MySQL failed.");
                        cb(err);
                    } else {
                        log.info("MySQL works.");
                        conn.end();
                        cb();
                    }
                });
            } catch(e){ cb(e); }},
            redis: function(cb) { try {
                log.info("Testing Redis...");
                var client = redis.createClient();
                client.on("ready", function(){
                    log.info("Redis works.");
                    client.end();
                    cb();
                });
                client.on("error", function(err){
                    log.error("Redis failed.");
                    cb(err);
                });
            } catch(e) { cb(e); }},
            php: function(cb) { try {
                log.info("Testing PHP...");
                child_process.exec("php -m", function(error, stdout, stderr){
                    if(error) {
                        log.error("PHP failed.");
                        return cb(error);
                    }
                    log.info("Got PHP modules...");
                    try {
                        var modules = require("ini").parse(stdout);
                        var pm = modules["PHP Modules"];
                        var reqs = [
                            // For workerman
                            "sysvmsg","sysvsem","sysvshm",
                            "pcntl","sockets",
                            // BIRD3
                            "PDO",
                        ];
                        var is_working = false;
                        for(var i=0; i<reqs.length; i++) {
                            var v = reqs[i];
                            if(!pm[v]) {
                                log.error("PHP failed.");
                                is_working = false;
                                return cb(new Error("PHP extension '"+v+"' not found."));
                            } else is_working = true;
                        }
                        if(is_working) {
                            log.info("PHP works.");
                            return cb();
                        }
                    } catch(e) {
                        cb(e);
                    }
                });
            } catch(e) { cb(e); }},
            phpVersion: function(step) { try {
                var semver = require("semver");
                var phpVersion = BIRD3.composer.require.php;
                log.info("Testing PHP version ("+phpVersion+")");
                child_process.exec('php -r "echo PHP_VERSION;"', function(err, stdout, stderr){
                    if(err) return step(err);
                    // One way to get around butchered PHP version strings.
                    // like: 5.5.29~1.dotdeb+7.1
                    try {
                        var myPhpVersion = stdout.match(/\d\.\d\.\d*/g)[0];
                        log.info("PHP Version is: "+myPhpVersion);
                        if(semver.satisfies(myPhpVersion, phpVersion)) {
                            log.info("PHP is version "+stdout);
                            step();
                        } else {
                            step(new Error("PHP is not compatible! Found: "+stdout));
                        }
                    } catch(e) {
                        // Fatal failure.
                        step(e);
                    }
                });
            } catch(e) { step(e); }}
        }, function(err, res){
            if(err) {
                console.error(err.stack || err);
                process.exit(1);
            } else {
                var com = require("BIRD3/Backend/Communicator")(null, redis);
                //require("BIRD3/Backend/Handlers/Error")();
                log.info("Starting: BIRD@"+BIRD3.package.version);
                var sub = redis.createClient();
                var redisClient = redis.createClient();
                sub.on("error", function(e){
                    log.error(e.stack);
                    process.exit(1);
                }).subscribe("BIRD3");
                sub.on("message", function(ch, data){
                    if(ch=="BIRD3") {
                        try {
                            var o = JSON.parse(data);
                            if(o.name=="bird3.exit") {
                                log.error("Shutting down the entire server.");
                                if(o.data) console.log(o.data);
                                house.kill();
                            }
                        } catch(e) {
                            log.notice("Received empty string: "+e.stack);
                        }
                    }
                });

                // We can use the RPC logger here
                require("BIRD3/Backend/Handlers/RpcLogger")();

                getports(3, function(err, ports){
                    if(err) {
                        log.error("Error finding a port: "+err);
                        process.exit(1);
                    }
                    /** All services use Hprose TCP to dish out an internal API
                        0: Hprose for Laravel
                        1: PHP statistics, workerman
                        3: trigger update / github/-lab webhook
                    */
                    log.info("Ports to be used: "+JSON.stringify(ports));
                    /*BIRD3.ports = {
                        hprose: ports[0],
                        phpStats: ports[1],
                        update: ports[2]
                    };*/
                    BIRD3.ports.hprose = ports[0];
                    // Error handling
                    run();
                });
            }
        });
    },
    shutdown: function(err, res) {
        BIRD3.log().info("BIRD3 has shut down.");
        process.exit(0);
    }
});
