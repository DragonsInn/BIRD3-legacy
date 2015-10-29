<?php namespace BIRD3\App\Entry;

use \App;

// Implementation
class Backend {
    public function __construct() {
        // The Main.php file should've done everything.
    }

    public function obtainState($cookies) {
        // Return the user's state off the cookie.
    }
}

// Install
App::on("WorkerStart", function($w){
    $h = App::hprose();
    $h->setDebugEnabled(true);
    $h->add(new Backend());
});
