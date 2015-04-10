<?php
// A totally polite NOOOOOOPE..
// Because it annoys me on CLI.
runkit_function_redefine("headers_sent", '', 'return false;');
// Header.
runkit_function_redefine(
    "header", '$to,$replace=false,$status=200',
    'return HttpResponse::header("Location: $to");'
);
// We have a custom handler.
runkit_function_redefine(
    "setcookie",
    '$name,$value,$expire=0,$path="/",$domain=null,$secure=false,$httponly=false',
    'return HttpResponse::setcookie($name,$value,$expire,$domain,$secure,$httponly);'
);
// Because...
runkit_function_redefine(
    "session_regenerate_id",
    '$deleteOld=false',
    'return bird3_session_regenerate_id($deleteOld);'
);

// ...hacky.
function exception_error_handler($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        // This error code is not included in error_reporting
        return;
    }
    echo "$message ($file : $line)\n";
    throw new ErrorException($message, 0, $severity, $file, $line);
}
set_error_handler("exception_error_handler");

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
function bird3_session_regenerate_id($delold=false) {
    if(session_status() == PHP_SESSION_ACTIVE) {
        // in session.c, I saw this:
        // PS(id) = PS(mod)->s_create_sid(&PS(mod_data), NULL TSRMLS_CC);
        // I dunno how to properly reproduce this...
        if($delold || !isset($_COOKIE["PHPSSID"])) {
            $id = openssl_random_pseudo_bytes(20);
            session_id($id);
            HttpResponse::setcookie("PHPSSID",$id,60*60*24*(30*6));
            return true;
        } else {
            session_id($_COOKIE["PHPSSID"]);
            return true;
        }
    } else return false;
}

// These classes are totally internal.
class HttpRequest {
    public function __construct($msg, $opt=[]) {
        foreach($msg->headers as $k=>$v) {
            $k = str_replace("-","_", strtoupper($k));
            $_SERVER["HTTP_".$k]=$v;
        }
        $_SERVER["REQUEST_METHOD"]=$msg->method;
        $_SERVER["REQUEST_URI"]=$msg->url;
        $_SERVER["SERVER_PROTOCOL"]="HTTP/".$msg->httpVersion;

        // Add additional options
        $_SERVER=array_merge($_SERVER, $opt);

        // Coooooookies. Nomnomnom...
        $_COOKIE = objectToArray($msg->cookies);

        if(strtolower($msg->method) == "post") {
            $_POST = objectToArray($msg->body);
        }

        $this->ctx = $msg;
    }

    private $ctx;
    public function __get($name) {
        return $this->ctx->{$name};
    }
}

class HttpResponse {
    public $hr=[
        "cookies"=>[
            #"name"=>["value", "opts"=>["expires"=>0, "maxAge"=>0, "secure"=>false, "httponly"=>false]]
        ],
        "headers"=>[
            "Content-type"=>"text/plain",
            "X-Meep"=>"o.o"
        ],
        "status"=>200,
        // Internal API
        "killme"=>false
    ];

    public $ctx;
    private static $self;
    public function __construct($res) {
        $this->ctx = $res;
        self::$self = $this;
    }

    // Helper
    public static function header($str, $replace=false, $status=null) {
        $self = null;
        if(!isset($this)) {
            $self = &self::$self;
        } else {
            $self = &$this;
        }
        if(substr($str, 0, 4) == "HTTP") {
            // This is a status message...
            list($vers, $status, $msg) = split(" ", $str);
            $self->hr["status"]=$status;
        } else {
            list($key, $val) = explode(":", $str, 2);
            $val = trim($val);
            $self->hr["headers"][$key]=$val;
        }
        if(!is_null($status)) self::status($status);
    }

    public static function status($s) {
        $self = null;
        if(!isset($this)) {
            $self = &self::$self;
        } else {
            $self = &$this;
        }
        $self->hr["status"]=$s;
    }

    public static function setcookie(
        $name, $value,
        $expire=0, $path="/", $domain=null,
        $secure=false, $httponly=false
    ) {
        $self = null;
        if(!isset($this)) {
            $self = &self::$self;
        } else {
            $self = &$this;
        }
        $path = (empty($path) ? "/" : $path);
        $self->hr["cookies"][$name] = [
            $value,
            "opts"=>[
                "maxAge"=>$expire,
                "path"=>$path,
                "domain"=>$domain,
                "secure"=>$secure,
                "signed"=>false,
                "httponly"=>$httponly
            ]
        ];
    }

    public function end($body) {
        return array_merge($this->hr, [
            "body"=>$body,
            "res"=>$this->ctx
        ]);
    }
}

// This app is exported to NodeJS.
class YiiApp {
    static function run($_req, $_res, $opts) {
        // I actually dont wanna. Hprose's serializer is much faster! D:
        $req = json_decode($_req);
        $res = json_decode($_res);

        // Stuff that we got
        $config = (object)$opts["config"];

        // Prepare to respond.
        $req = new HttpRequest($req, $opts["_SERVER"]);
        $res = new HttpResponse($res);

        $GLOBALS["req"]=$req;
        $GLOBALS["res"]=$res;

        try {
            // Run Yii
            ob_start();
            $res->header("Content-type: text/html");

            $fname = $config->base.$req->url;
            if(file_exists($fname) && is_file($fname)) {
                $fnp = explode(".", $fname);
                $ext = array_pop($fnp);
                if($ext == "php") {
                    require($fname);
                } else {
                    echo file_get_contents($fname);
                }
            } else {
                // Yii stuff
                $_SERVER["SCRIPT_NAME"]="/app.php";
                $_SERVER["DOCUMENT_URI"]="/app.php";
                $_SERVER["SCRIPT_FILENAME"]=realpath($config->base."/app.php");

                // change the following paths if necessary
                $config=dirname(__FILE__).'/../protected/config/main.php';

                // remove the following line when in production mode
                defined('YII_DEBUG') or define('YII_DEBUG',true);

                $c=include_once($config);
                Yii::createWebApplication($c);
                set_error_handler("exception_error_handler");
                Yii::app()->run();
            }

            $o_res = ob_get_contents();
            ob_end_clean();
        } catch(\Exception $e) {
            $o_res = ob_get_contents();
            ob_end_clean();
            $res->status(500);
            $res->header("Content-type: text/pain");
            $o_res .= "\n\nEXCEPTION[ ".int2err($e->getCode())." ]: ".$e->getMessage()."\n";
            $o_res.= "At: ".$e->getFile()."@".$e->getLine()."\n";
            $o_res.= $e->getTraceAsString();
        }

        $res->hr["killme"]=true;
        return $res->end($o_res);
    }

    static function stop() {
        Log::info("Reloading");
        posix_kill(getmypid(), SIGUSR1);
    }
}
