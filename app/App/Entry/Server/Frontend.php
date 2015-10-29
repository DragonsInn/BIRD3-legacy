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

// Facades
use \App;

// ...hacky.
function exception_error_handler($severity, $message, $file, $line) {
    $output = "$message ($file : $line)";
    Log::error($output);
    throw new ErrorException($message, 0, $severity, $file, $line);
}

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

// Re-Creation
// FIXME: Re-write the Session class instead.
/* function bird3_session_regenerate_id($delold=false) {
    Log::info("Regenerating PHP Session ID (deleteOld=".($delold ? "true":"false").")");
    if(session_status() == PHP_SESSION_ACTIVE) {
        // in session.c, I saw this:
        // PS(id) = PS(mod)->s_create_sid(&PS(mod_data), NULL TSRMLS_CC);
        // I dunno how to properly reproduce this...
        if($delold || !isset($_COOKIE["PHPSESSID"])) {
            $id = base64_encode(openssl_random_pseudo_bytes(20));
            session_id($id);
            HttpResponse::setcookie("PHPSESSID",$id,60*60*24*(30*6));
            return true;
        } else {
            session_id($_COOKIE["PHPSESSID"]);
            return true;
        }
    } else return false;
} */

// This app is exported to NodeJS.
class Frontend {
    static function handle($ctx) {
        ini_set("session.serialize_handler", "php_serialize");

        // Dump the context into the current scope.
        foreach($ctx as $name=>$data) {
            ${$name} = (object)$data;
        }

        /*
            In here, we should start up the Laravel app, feed it with the
            information that it needs in order to perform a request-response.

            The good thing is, that if this fails, HPROSE will pick the exception
            up, and distribute it to NodeJS - which will result in an error event.

            Thakfully, Laravel ALSO has an exceptin handler. This should account
            for a majority of failsafety.

            Hopefuly though, this does work...

            For this to work:
                - Obtain the application instance
                - Create a Request, get a response
                - Serialize the data and send it back to NodeJS.

            Important is:
                - Reponse body and headers must be separated.
        */

        try {
            // Create a kernel with a WebApplication class instead of Server.
            $router = App::make(Router::class);
            #$app = require_once(APP_ROOT."/app/bootstrap/app.php");
            $app = App::getInstance();
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
            if(isset($request->body)) {
                $request->setRawBody($request->body);
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
            return [
                "status"        => $responseCtx->getStatusCode(),
                #"statusText"    => $responseCtx->getStatusText(),
                "statusText" => "OK",
                "headers"       => $headers,
                "cookies"       => $cookies,
                "body"          => $responseCtx->getContent(),
            ];
        } catch(\Exception $e) {
            Log::error(var_export($e->stack, true));
        }
    }
}

// Register. Holy crap, and for this alone I love Lara.
App::on("WorkerStart", function($w){
    $h = App::hprose();
    $h->setDebugEnabled(true);
    $h->addClassMethods(Frontend::class, null, Frontend::class);
});
