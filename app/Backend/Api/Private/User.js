// Export some essential Yii APIs through SC.
var hprose = require("hprose"),
    async = require("async"),
    mysql = require("mysql"),
    redis = require("redis").createClient(),
    cookie = require("cookie"),
    unserialize = require("phpjs").unserialize,
    lightOrm = require("light-orm");

// Little utility
function getYiiSessionValue(prefix, key, obj) {
    return obj[prefix+"__"+key];
}

module.exports = function(BIRD3) {
    var rpc = BIRD3.rpc;

    async.parallel({
        mysql: function(step) {
            var conn = lightOrm.driver = mysql.createConnection({
                host:       "localhost",
                user:       config.DB.user,
                password:   config.DB.pass,
                database:   config.DB.mydb
            });
            conn.connect(function(e){
                if(e) return step(e);
                else  step(null, conn);
            });
            conn.on("error", function(e){
                BIRD3.notice(e);
                BIRD3.info("Reconnecting MySQL in 1 second...");
                setTimeout(conn.connect, 1000);
            });
        },
        hprose: function(step) {
            redis.get("bird3.hprosePort", function(err, portnr){
                if(err) return step(err);
                else {
                    portnr = Number(portnr);
                    var client = new HproseTcpClient("tcp://127.0.0.1:"+portnr);
                    client.on("error", BIRD3.error);
                    step(null, client);
                }
            });
        }
    }, function(err, res){
        if(err) {
            BIRD3.error(err);
            //process.exit(0); fixme: emit bird3.exit
        } else {
            var mysql = res.mysql,
                hprose = res.hprose;

            BIRD3.io.on("connection", function(sock){
                sock.on("update_user_visit", function(cookies){
                    var parsedCookies = cookie.parse(cookies);
                    hprose.invoke("obtainState", [parsedCookies], function(res){
                        // res is now the Yii state! res: {yii, prefix}
                        // Yii saves a ridiculous internal structure: id, userName, duration, states.
                        // .... Grab that stuff.
                        var yii = res.yii,
                            id = yii[0],
                            userName = yii[1],
                            duration = yii[2],
                            states = yii[3],
                            PHPSESSID = parsedCookies.PHPSESSID || false,
                            keyPrefix = res.prefix;

                        // We need to make sure the user is not being funny with us.
                        // In other words: Avoid user-impersonification by counter-checking session.
                        var key = "BIRD3.Session."+PHPSESSID;
                        redis.get(key, function(err, res2){
                            if(err) throw err;
                            if(res2 == null) return;
                            var yiiSession = unserialize(res2);
                            var s_id = getYiiSessionValue(keyPrefix, "id", yiiSession),
                                s_name = getYiiSessionValue(keyPrefix, "name", yiiSession);

                            if(id == s_id && userName == s_name) {
                                // The user is NOT doing anything funny on us here. PHEW.
                                // FIXME: Somehow save this state of authentification...
                                var Users = new lightOrm.Collection("users");
                                Users.findOne({id: id}, function(err, $model){
                                    $model.set("lastvisit_at", Date.now());
                                    $model.update(function(err, $model){
                                        if(err) throw err;
                                        sock.emit("client_log", "Lastvisit updated.");
                                    });
                                });
                            }
                        });
                    });
                });
            });
        }
    });
}
