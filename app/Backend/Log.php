<?php  namespace BIRD3\Backend;

use Predis\Client;
use Exception;

class Log {
    private static $redis = null;
    public static function getRedis() {
        if(self::$redis==null) {
            self::$redis = new Client;
            self::$redis->connect();
            $tries = 5; $connected = null;
            while(!$connected) {
                try {
                    $connected = self::$redis->isConnected();
                } catch(Exception $e) {
                    $connected = false;
                }
                if($tries == 0)
                    throw new Exception("Could not connect to Redis.");
                $tries--;
                sleep(0.5);
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
        try {
            return self::getRedis()->publish("BIRD3", $msg);
        } catch(Exception $e) {
            // Try again. Sometimes, Redis just goes flop.
            // Probably has to do with Workerman's forkaholism. o.o
            return call_user_func_array([__CLASS__, $method], $args);
        }
    }
}
