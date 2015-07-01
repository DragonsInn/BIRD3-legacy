<?php class Log {
    private static $redis = null;
    public static function getRedis() {
        if(self::$redis==null) {
            self::$redis = new Redis;
            $rt = self::$redis->popen("127.0.0.1");
            if($rt!=true) {
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
