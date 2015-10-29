<?php namespace BIRD3\Foundation\WebDriver;

use Illuminate\Http\Response as LaravelResponse;

class Response extends LaravelResponse {

    // We send then when we return to hprose.
    // Hence, there is nothing we can do here. Or rather, nothing we have to.
    public function sendHeaders() {
        return $this;
    }
    public function sendContent() {
        return $this;
    }
    public function send() {
        return $this;
    }

    // Ugh, i dont get why this is not possible.
    public function getStatusText() {
        return $this->statsText;
    }

}
