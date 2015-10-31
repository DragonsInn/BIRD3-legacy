<?php namespace BIRD3\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Hprose extends Facade {

    protected static function getFacadeAccessor() {
        return \BIRD3\Support\HproseHolder::class;
    }

}
