<?php namespace BIRD3\Support;

use \Exception;
use FlorianWolters\Component\Util\Singleton\SingletonTrait;

/**
 * Singleton trait.
 *
 * Use this and all your public methods are staticially accessible,
 * as well as persistent.
 */
trait __Singleton {
    private static $_cls_instance = null;
    private function __construct() {
        if(method_exists($this, "init")) {
            call_user_method("init", $this);
        }
    }
    static function getInstance() {
        if(self::$_cls_instance == null) {
            self::$_cls_instance = new self();
        }
        return self::$_cls_instance;
    }
    public static function __callStatic($name, $args) {
        echo "__staticCall: $name\n";
        if(method_exists(self::getInstance(), "_".$name)) {
            return call_user_func_array([self::getInstance(), "_".$name], $args);
        }
    }
    public function __call($name, $args) {
        echo "__call: $name\n";
        if(method_exists($this, $name)) {
            return call_user_func_array([$this, $name], $args);
        } else return $this->__callStatic($name, $args);
    }
    public function __clone() {
        throw new Exception("You can not clone a singleton!");
    }
}
