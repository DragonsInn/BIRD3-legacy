<?php
// Get rid of replicas
set_include_path(__DIR__."/../php_modules".PATH_SEPARATOR.get_include_path());

require_once "ParseArgs.php";
require_once "hprose/Hprose.php";
require_once "workerman/workerman/Autoloader.php";
require_once "hprose-workerman/hprose-workerman.php";

// The global server instance
class AppServer {
    private static $_worker;
    public static function worker() { return self::$_worker; }

    private static $_hprose;
    public static function hprose() { return self::$_hprose; }

    public static $ctx;

    private static $_init=false;
    static function initialize($host, $port, $worker) {
        self::$_worker = new \hprose\Workerman($host, $port);
        self::$_worker->count = $worker;
        self::$_worker->name = "hprose (BIRD3)";
        self::$_worker->reloadable = true;
        self::$_hprose = self::$_worker->hprose();
        self::$ctx = new stdClass;
        self::$_init = true;
    }
}

function println($msg) { echo "$msg\n"; }

function initialize() {
    // This is a test.
    AppServer::worker()->add("foo", function(){
        return "bar";
    });
}

# Get options...
if($argv[1] == "start") {
    $args = parseArgs($argv);
    if(!isset($args["host"]) or !isset($args["port"]) or !isset($args["workers"])) {
        die("Not enough arguments! --host --port --workers are required.");
    } else {
        extract($args);
        AppServer::initialize($host, $port, $workers);
        initialize();
    }
}

# Start the running.
\Workerman\Worker::runAll();
