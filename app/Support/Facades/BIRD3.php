<?php namespace BIRD3\Support\Facades;

use Illuminate\Support\Facades\Facade;

class BIRD3 extends Facade {

    protected static function getFacadeAccessor() {
        return \BIRD3\Support\BIRD3Helper::class;
    }

}
