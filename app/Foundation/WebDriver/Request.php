<?php namespace BIRD3\Foundation\WebDriver;

use Exception;
use InvalidArgumentException;
use Illuminate\Http\Request as LaravelRequest;

class Request extends LaravelRequest {

    // Create using: createRequestFromFactory
    // Factory sig: $requestFactory($query, $req, $attributes, $cookies, $files, $server, $content)

    // THIS IS A MONSTER! HOT DA...DOG!
    public function __construct(
        array $query = array(), array $request = array(), array $attributes = array(),
        array $cookies = array(), array $files = array(), array $server = array(),
        $content = null
    ) {
        if(isset($attributes["optional"])) {
            $this->optional = $attributes["optional"];
            unset($attributes["optional"]);
        }
        parent::__construct(
            $query, $request, $attributes,
            $cookies, $files, $server,
            $content
        );
    }

    // Factory method to create a request from globals.
    // well, no! We can not use them.
    public static function createFromGlobals() {
        throw new Exception("Can not generate request from globals in a hprose environment.");
    }

    // We do not want our globals to ve overridden!
    public function overrideGlobals() {
        throw new Exception("Overriding the GLOBALS in a hprose environment is not permitted.");
    }

    // Hprose stuff: Setting and getting the request body content.
    private $rawBody = false;
    public function getRawBody() {
        return $this->rawBody == false ? "" : $this->rawBody;
    }
    public function setRawBody($body) {
        if(!is_string($body) && !is_null($body)) {
            $msg = "The request body has to be a string. Got: ".gettype($body);
            throw new InvalidArgumentException($msg);
        }
        if($this->rawBody != false) {
            throw new Exception("The request body can be set only once.");
        }
        $this->rawBody = $body;
        return $this;
    }

    // This is a slightly re-written method.
    // It allows to retrieve the hprose request body.
    public function getContent($asResource = false) {
        $currentContentIsResource = is_resource($this->content);
        if (PHP_VERSION_ID < 50600 && false === $this->content) {
            throw new \LogicException('getContent() can only be called once when using the resource return type and PHP below 5.6.');
        }
        if (true === $asResource) {
            if ($currentContentIsResource) {
                rewind($this->content);
                return $this->content;
            }
            // Content passed in parameter (test)
            if (is_string($this->content)) {
                $resource = fopen('php://temp', 'r+');
                fwrite($resource, $this->content);
                rewind($resource);
                return $resource;
            }
            $this->content = false;
            return fopen('php://input', 'rb');
        }
        if ($currentContentIsResource) {
            rewind($this->content);
            return stream_get_contents($this->content);
        }
        if (null === $this->content) {
            $this->content = $this->getRawBody();
        }
        return $this->content;
    }

    // Allow the getting of optional data
    private $optional;
    public function optional($key = null) {
        if($key == null) {
            return $this->optional;
        } else {
            return array_get($this->optional, $key);
        }
    }
}
