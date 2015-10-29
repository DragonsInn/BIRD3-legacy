<?php namespace BIRD3\Foundation;

// Pull in the deps
use stdClass;
use Workerman\Hprose as HproseWorkerman;
use Evenement\EventEmitter;
use BIRD3\Foundation\WebDriver\Contracts\WebDriver as WebDriverContract;
use BIRD3\Foundation\BaseApplication;
use BIRD3\Backend\Log;

set_exception_handler(function($e){
    Log::error($e->getMessage());
});

/*
    This is the main backend application that we will be using.
*/
class ServerApplication extends BaseApplication implements WebDriverContract {

    // Keep and return reference to worker.
    private static $_worker;
    public static function &worker() { return self::$_worker; }

    // And to hprose.
    private static $_hprose;
    public static function &hprose() { return self::$_hprose; }

    // Binding to the current context, shared.
    public static $ctx;

    // A different aproach to Singleton-ish stuff...
    private static $_s_instance=null;
    static function getSelf() { return self::$_s_instance; }
    private function setSelf(&$instance) {
        if(self::$instance != null) return;
        self::$_s_instance = $instance;
    }
    public static function __callStatic($name, $args) {
        if(self::$_s_instance == null) {
            throw new \Exception("No instance of ".__CLASS__." had previously been made.");
        }
        return call_user_func_array(
            [self::getInstance(), $name],
            $args
        );
    }

    // Enable eventing and prevent public instantiation.
    private $ee;
    public function __construct($host, $port, $worker = 2, $name = "hprose (WebDriver)") {
        # Import
        require_once("./bootstrap/paths.php");
        $configure = require_once("./bootstrap/configure.php");
        # Construct
        parent::__construct(APP_ROOT, CONFIG_ROOT);
        $configure($this);
        $this->bootstrapConsole();

        $this->setSelf($this);

        $this->ee = new EventEmitter();

        self::$_worker = new HproseWorkerman($host, $port);
        self::$_worker->count = $worker;
        self::$_worker->name = $name;
        self::$_worker->reloadable = true;
        self::$_hprose = self::$_worker->hprose();
        self::$ctx = new stdClass;

        // Eventing. Map the events back and forth.
        foreach([
            "WorkerStart", "WorkerStop",
            "Connect", "Close",
            "Error",
            "BufferFull", "BufferEmpty"
        ] as $event) {
            $prop = "on".$event;
            $self = $this;
            Log::info("Registering $prop event...");
            self::$_worker->{$prop} = function() use($event, $self) {
                $self->emit($event, func_get_args());
            };
        }
    }

    // Now for the public. Mimic the stuff.
    public function on($ev, $cb) {
        return $this->ee->on($ev, $cb);
    }
    public function emit() {
        return call_user_func_array([$this->ee, "emit"], func_get_args());
    }

    static function InitializeAndRun(array $arguments) {
        list($host, $port, $name) = $arguments;
        $self = new self($host, $port, $name);

        // Search and activate allt he other entries.
        // I totally love my resolver. Period.
        $entries = glob(resolve("@app/Entry/Server/*.php"));
        foreach($entries as $entry) {
            echo "Including: $entry\n";
            require_once($entry);
        }
    }
}
