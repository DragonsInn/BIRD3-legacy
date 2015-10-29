<?php namespace BIRD3\Support;

use FlorianWolters\Component\Util\Singleton\SingletonTrait;

class GlobalConfig {
    use SingletonTrait;

    private $config;

    private function __construct() {
        // o.o
    }

    static function load($path) {
        $self = self::getInstance();
        $self->config = parse_ini_file($path, true);
        return $self;
    }

    static function get($key) {
        $self = self::getInstance();
        return array_get($self->config, $key);
    }
}
