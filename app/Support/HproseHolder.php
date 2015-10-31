<?php namespace BIRD3\Support;

use InvalidArgumentException as IVE;

class HproseHolder {
    private $store;
    public function __construct($data) {
        if(!is_array($data)) {
            throw new IVE("Argument 1 is ".gettype($data).", should be array.");
        }
        $this->store = $data;
    }
    public function get($key) {
        return array_get($this->store, $key);
    }
}
