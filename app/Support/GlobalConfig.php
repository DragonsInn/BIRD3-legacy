<?php namespace BIRD3\Support;

require_once __DIR__."/Helpers.php";

use FlorianWolters\Component\Util\Singleton\SingletonTrait;
use Spyc;
use Exception;

class GlobalConfig {
    use SingletonTrait;

    private $config = false;

    private function __construct() {
        // o.o
    }

    static function load() {
        if(!function_exists("find_root")) {
            throw new Exception("Need to be able to find root.");
        }
        $self = self::getInstance();
        $root = find_root(__DIR__);
        $path = "$root/config/BIRD3.yml";
        $yamlArray = Spyc::YAMLLoad($path);
        # FIXME: Spyc needs to implement objects or something.
        # $yamlObject = json_encode(json_decode($yamlArray));
        $self->config = $yamlArray;
        return $self;
    }

    static function get($key) {
        $self = self::getInstance();
        if(!$self->config) self::load();
        return array_get($self->config, $key);
    }

    public function all() {
        return $this->config;
    }
}
