<?php namespace BIRD3\Foundation\WebDriver;

use Illuminate\Http\Response as LaravelResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

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

    // Allow the morphing of data
    public function __construct($content = "", $status = 200, $headers = array()) {
        if($headers instanceof ResponseHeaderBag) {
            $this->headers = $headers;
        } else {
            $this->headers = new ResponseHeaderBag($headers);
        }
        $this->setContent($content);
        $this->setStatusCode($status);
        $this->setProtocolVersion('1.1'); # We are modern, yo.
    }

}
