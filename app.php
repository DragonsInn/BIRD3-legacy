<?php if(function_exists("setproctitle")) setproctitle("BIRD3: Workerman Master");
$root=__DIR__;
echo $root."\n";
require_once "$root/php_modules/autoload.php";
require_once "$root/php_modules_ext/ParseArgs.php";
require_once "$root/php_modules/walkor/workerman/Autoloader.php";
require_once "$root/php_modules_ext/hprose-workerman/HproseWorkermanService.php";
require_once "$root/php-lib/Log.php";

// The global server instance
class AppServer {
    private static $_worker;
    public static function &worker() { return self::$_worker; }

    private static $_hprose;
    public static function &hprose() { return self::$_hprose; }

    public static $ctx;

    private static $_preinit=false;
    static function pre_initialize() {
        if(self::$_preinit) return;
        // Now, for that eventing... Part 1.
        self::$inst = new self();
    }

    private static $_init=false;
    static function initialize($host, $port, $worker) {
        if(self::$_init) return;
        self::$_worker = new \Workerman\Hprose($host, $port);
        self::$_worker->count = $worker;
        self::$_worker->name = "hprose (BIRD3)";
        self::$_worker->reloadable = true;
        self::$_hprose = self::$_worker->hprose();
        self::$ctx = new stdClass;
        self::$_init = true;

        // Eventing, part 2.
        self::$_worker->onWorkerStart = function() {
            self::emit("start", func_get_args());
        };
        self::$_worker->onWorkerStop = function($w) {
            self::emit("stop", func_get_args());
        };
        self::$_worker->onConnect = function($w) {
            self::emit("connect", func_get_args());
        };
        self::$_worker->onClose = function() {
            self::emit("close", func_get_args());
        };
        self::$_worker->onError = function() {
            self::emit("error", func_get_args());
        };
        self::$_worker->onBufferFull = function() {
            self::emit("buffer_full", func_get_args());
        };
        self::$_worker->onBufferEmpty = function() {
            self::emit("buffer_empty", func_get_args());
        };
    }

    // Enable eventing and prevent public instantiation.
    private static $inst;
    private static $ee;
    private function __construct() {
        self::$ee = new \Evenement\EventEmitter();
    }

    // Now for the public. Mimic the stuff.
    static function on($ev, $cb) {
        #echo "On: $ev\n";
        return self::$ee->on($ev, $cb);
    }
    static function emit() {
        #echo "Emitting...\n";
        #print_r(func_get_args());
        return call_user_func_array([self::$ee, "emit"], func_get_args());
    }
}

function println($msg) { echo "$msg\n"; }

function initialize() {
    global $root;
    foreach(glob("$root/php-lib/*_worker.php") as $file) {
        require_once($file);
    }
}

# Get options...
$args = parseArgs($argv);
if($argv[1] == "start" || $argv[1] == "restart") {
    if(!isset($args["host"]) or !isset($args["port"]) or !isset($args["workers"])) {
        die("Not enough arguments! --host --port --workers are required.");
    } else {
        AppServer::pre_initialize();
        initialize();
        extract($args);
        AppServer::initialize($host, $port, $workers);
    }
}

# Start the running.
\Workerman\Worker::$stdoutFile = "$root/log/php.log";
\Workerman\Worker::$logFile = "$root/log/workerman.log";
\Workerman\Worker::runAll();
