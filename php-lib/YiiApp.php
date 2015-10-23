<?php
// A totally polite NOOOOOOPE..
// Because it annoys me on CLI.
// Header.
runkit_function_redefine("headers_sent", '', 'return false;');
runkit_function_redefine(
    "header", '$to,$replace=false,$status=200',
    'return HttpResponse::header($to);'
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
    $output = "$message ($file : $line)";
    Log::error($output);
    throw new ErrorException($message, 0, $severity, $file, $line);
}
#set_error_handler("exception_error_handler");
ini_set("session.serialize_handler", "php_serialize");

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
}

// These classes are totally internal.
class HttpRequest {
    public function __construct() {
    }
}

class HttpResponse {
    public $hr=[
        "cookies"=>[
            /*
            "name"=>[
                "value",
                "opts"=>[
                    "expires"=>0,
                    "maxAge"=>0,
                    "secure"=>false,
                    "httponly"=>false
                ]
            ]
            */
        ],
        "headers"=>[
            "Content-type"=>"text/plain",
            "X-Meep"=>"o.o"
        ],
        "status"=>200,
        // Internal API
        "killme"=>true
    ];

    private static $self;
    public function __construct() {
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
            list($vers, $status, $msg) = explode(" ", $str);
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
            "body"=>$body
        ]);
    }
}

// This app is exported to NodeJS.
class YiiApp {
    static function run($preq, $opt) {
        // Install the CLI stuff
        set_error_handler("exception_error_handler");
        try {
            // Prepare to run a sub process
            $spec = [
                0 => ["pipe", "r"], # STDIN
                1 => ["pipe", "w"], # STDOUT
                2 => ["pipe", "w"]  # STDERR
            ];
            $cmd = implode(" ",[PHP_BINARY, __DIR__."/executor.php"]);
            $expose = [
                "env" => $_ENV,
                "req" => $preq,
                "opt" => $opt
            ];
            $env = ["CONFIG" => hprose_serialize($expose)];
            $ph = proc_open($cmd, $spec, $pipes, __DIR__,$env);
            $res = new HttpResponse();
            if(is_resource($ph)) {
                // Get STDOUT/-ERR
                // @meme All your error are belong to you.
                $stdout = stream_get_contents($pipes[1]);
                $stderr = stream_get_contents($pipes[2]);
                foreach($pipes as $p=>$k) fclose($pipes[$p]);
                $rtv = proc_close($ph);
                $out = '';
                if($rtv > 0) {
                    // Log the failure, at least.
                    Log::error("PHP exited with $rtv.");
                }
                try {
                    $out = hprose_unserialize($stdout);
                } catch(\Exception $e) {
                    // Yii MIGHT had caught an exception.
                    // Hence, hprose can not parse it...since its raw HTML...
                    // ...because Yii kills my output buffers. >v<
                    // Therefore, print that HTML out. Meh.
                    #Log::warn("Response was unclear. ".$e);
                    $res->header("Content-type: text/html");
                    $res->status(200);
                    $out = $res->end($stdout);
                }
            } else {
                Log::error("Unable to process request!");
                $res->header("Content-type: text/plain");
                $res->status(500);
                $out = $res->end("INTERNAL: Process could not be launched.");
            }
            return $out;
        } catch(\Exception $e) {
            // Just die this one out. Period.
            Log::error("A fatal error occured. This request is dead. "+$e);
            print_r($e);
            return "o.o; Oh dear.";
        }
    }
}
