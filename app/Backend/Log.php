<?php  namespace BIRD3\Backend;

use Predis\Client;

class Log {
    private static $redis = null;
    public static function getRedis() {
        if(self::$redis==null) {
            self::$redis = new Client;
            if(self::$redis->isConnected()) {
                echo "Error: Can not connect to Redis!!";
                exit(1);
            }
        }
        return self::$redis;
    }
    static function __callStatic($method, $args) {
        $msg = json_encode([
            "name"=>"rpc.log",
            "data"=>[
                "method"=>$method,
                "args"=>$args
            ]
        ]);
        if($method==="error") {
            $pmsg = implode(" ",$args);
            echo "[ERROR]: $pmsg\n";
        }
        return self::getRedis()->publish("BIRD3", $msg);
    }
}
