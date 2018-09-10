<?php namespace BIRD3\App\Entry\Server;

// PHP
use ErrorException;

// Laravel
use Illuminate\Routing\Router;

// BIRD3
use BIRD3\Backend\Log;
use BIRD3\Backend\Http\Kernel as HttpKernel;
use BIRD3\Foundation\WebDriver\Request;
use BIRD3\Foundation\WebDriver\Response;
use BIRD3\Support\HproseHolder;

// Facades
use App;

function objectToArray($d) {
    if (is_object($d)) {
        // Gets the properties of the given object
        // with get_object_vars function
        $d = get_object_vars($d);
    }
    if (is_array($d)) {
        /*
        * Return array converted to object
        * Using __FUNCTION__ (Magic constant)
        * for recursive call
        */
        return array_map(__FUNCTION__, $d);
    } else {
        // Return array
        return $d;
    }
}

function int2err($ec) {
    switch($ec) {
        case E_ERROR:               return "E_ERROR";
        case E_WARNING:             return "E_WARNING";
        case E_PARSE:               return "E_PARSE";
        case E_NOTICE:              return "E_NOTICE";
        case E_CORE_ERROR:          return "E_CORE_ERROR";
        case E_CORE_WARNING:        return "E_CORE_WARNING";
        case E_COMPILE_ERROR:       return "E_COMPILE_ERROR";
        case E_COMPILE_WARNING:     return "E_COMPILE_WARNING";
        case E_USER_ERROR:          return "E_USER_ERROR";
        case E_USER_WARNING:        return "E_USER_WARNING";
        case E_USER_NOTICE:         return "E_USER_NOTICE";
        case E_STRICT:              return "E_STRICT";
        case E_RECOVERABLE_ERROR:   return "E_RECOVERABLE_ERROR";
        case E_DEPRECATED:          return "E_DEPRECATED";
        case E_USER_DEPRECATED:     return "E_USER_DEPRECATED";
        case E_ALL:                 return "E_ALL";
        default:                    return $ec;
    }
}

// This app is exported to NodeJS.
class Frontend {
    static function handle($ctx) {
        ini_set("session.serialize_handler", "php_serialize");

        set_error_handler(function($severity, $message, $file, $line) {
            $e = new ErrorException($message, 0, $severity, $file, $line);
            $output = "$message ($file : $line)".PHP_EOL.$e->getTraceAsString();
            Log::error($output);
            throw $e;
        });

        // Dump the context into the current scope.
        foreach($ctx as $name=>$data) {
            ${$name} = (object)$data;
        }

        // Store the hprose parameters, the optional ones.
        if(!App::bound(HproseHolder::class)) {
            App::instance(HproseHolder::class, new HproseHolder());
        }
        \Hprose::setContext($ctx["optional"]);
        $wpA = \Hprose::get("wpHash");
        $wpB = $ctx["optional"]["wpHash"];
        assert($wpA == $wpB, "WebPack hash does not match! ($wpA == $wpB)");

        $app = App::getInstance();
        $router = $app["router"];

        // Build a HTTP kernel using the ServerApplication instance,
        // effectively treatening it like a WebApplication.
        $kernel = new HttpKernel($app, $router);

        // Create a request off the hprose parameters
        # create($uri, $method = 'GET', $parameters = array(), $cookies = array(), $files = array(), $server = array(), $content = null)
        $requestCtx = Request::create(
            $request->url,
            $request->method,
            $request->postData,
            $request->cookies,
            $request->files,
            $request->server
        );

        // If we have a request body, set it.
        // FIXME: Uh. Raw bodies ... might be different. o.o
        if(isset($request->body)) {
            $requestCtx->setRawBody($request->body);
        }

        // Obtain a response.
        // Sigh, I want this to be a REAL WebDriver response...
        $responseCtx = $kernel->handle($requestCtx);

        // Handle remaining things
        $kernel->terminate($requestCtx, $responseCtx);

        // The request is done, so convert and return it.
        // # Headers
        $headers = [];
        foreach($responseCtx->headers->allPreserveCase() as $name=>$values) {
            foreach($values as $value) {
                $headers[] = [$name, $value];
            }
        }

        // # Cookies
        $cookies = [];
        foreach($responseCtx->headers->getCookies() as $cookie) {
            $cookies[] = [
                "name"      => $cookie->getName(),
                "value"     => $cookie->getValue(),
                "options"   => [
                    "expires"   => $cookie->getExpiresTime(),
                    "path"      => $cookie->getPath(),
                    "domain"    => $cookie->getDomain(),
                    "secure"    => $cookie->isSecure(),
                    "httpOnly"  => $cookie->isHttpOnly()
                ]
            ];
        }

        // # Status
        if($responseCtx instanceof Request) {
            $statusText = $responseCtx->getStatusText();
        } else {
            $statusText = null;
        }

        return [
            "status"        => $responseCtx->getStatusCode(),
            "statusText"    => $statusText,
            "statusText"    => $statusText,
            "headers"       => $headers,
            "cookies"       => $cookies,
            "body"          => $responseCtx->getContent(),
        ];
    }
}

// Register. Holy crap, and for this alone I love Lara.
App::on("WorkerStart", function($w){
    $h = App::hprose();
    $h->setDebugEnabled(true);
    $h->addClassMethods(Frontend::class, null, Frontend::class);
});
