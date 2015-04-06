<?php
/**********************************************************\
|                                                          |
|                          hprose                          |
|                                                          |
| Official WebSite: http://www.hprose.com/                 |
|                   http://www.hprose.org/                 |
|                                                          |
\**********************************************************/

/**********************************************************\
 *                                                        *
 * Hprose/Workerman/Service.php                           *
 *                                                        *
 * hprose service class for php 5.3+                      *
 * This client version supports the Workerman functions.  *
 *                                                        *
 * LastModified: Apr 6, 2015                              *
 * Author: Kevin Ingwersen <ingwie2000@gmail.com>         *
 *         http://ingwie.me
 *                                                        *
\**********************************************************/


namespace hprose;

/**
 * @file
 * This file contains functionality to hook into the Workerman system.
 * The user is responsible to include the proper classes (Workerman/Autoloader.php).
 */

/**
 * This is a soft wrapper around the \Hprose\Service class. It accepts \Workerman\Worker
 * derived classes and provides access into the hprose system.
 * It could be considered an abstraction.
 *
 * This class is ment to be used only internally. It should not be used by
 * anything else but WorkermanHprose.
 */
class WorkermanService extends Service {
    private $worker;
    public $ctx;
    public function __construct(\Workerman\Worker &$worker) {
        $this->worker = $worker;
        $this->ctx = new \stdClass;
    }
    public function handle(&$conn, $request) {
        $request = ltrim($request);
        $len = strlen($request);
        echo "Got($len): $request\n";
        $res = $this->defaultHandle($request, $this->ctx);
        echo "Sent: $res\n";
        $conn->send($res);
    }
}


/**
 * This is the actual class that provides the bindings.
 * It overrides the onMessage callback to handle it with hprose.
 * It provides a method to access a reference of the original hprose
 * instance and also a shorthand method to add functions/class methods.
 * It is recommended to use the actual hprose api.
 *
 * An example of how it is used:
 *
 * ```php
 * <?php
 * include "hprose-php/Hprose.php";
 * include "Workerman/Autoloader.php";
 *
 * function hello($w) { return "Hello, $w!"; }
 *
 * $client = new \hprose\Workerman("127.0.0.1", 9999);
 * $client->count = 4; # Make 4 workers.
 * $hprose = $client->hprose();
 * $hprose->addFunction("hello");
 *
 * Worker::runAll();
 * ?>
 * ```
 *
 * From now on, there is a server on localhost:9999, ready to take hprose commands!
 */
class Workerman extends \Workerman\Worker {
    // Initialize
    private $_hprose;
    public function __construct($host, $port, $opts = array()) {
        parent::__construct("tcp://{$host}:{$port}", $opts);
        $this->name = "hprose";
        $this->_hprose = new WorkermanService($this);
    }

    public function &hprose() { return $this->_hprose; }

    // Setup the methods
    public function run() {
        $this->onMessage = array($this, 'onMessage');
        parent::run();
    }

    // The handler
    public function onMessage($conn, $data) {
        $this->_hprose->handle($conn, $data);
    }

    // Adding functions to hprose... in a cheaty way.
    public function add($name, $fnc) {
        if(is_string($name) && (is_callable($fnc) || is_array($fnc))) {
            return $this->_hprose->addFunction($fnc, $name);
        }
    }
}
