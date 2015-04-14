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
        if($delold || !isset($_COOKIE["PHPSESSID"])) {
            $id = openssl_random_pseudo_bytes(20);
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
            #"name"=>["value", "opts"=>["expires"=>0, "maxAge"=>0, "secure"=>false, "httponly"=>false]]
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
            "body"=>$body
        ]);
    }
}

// This app is exported to NodeJS.
class YiiApp {
    static function run($preq, $opt) {
        // Stuff that we got
        $config = $opt["config"];
        $_ENV["userData"]=$opt["userData"];

        // Convert some arrays.
        foreach($preq["request"] as $key=>$val) {
            $GLOBALS[$key]=array_merge($GLOBALS[$key], $val);
        }

        // Prepare to respond.
        $req = new HttpRequest();
        $res = new HttpResponse();

        $GLOBALS["req"]=$req;
        $GLOBALS["res"]=$res;

        /*try {
            $sb = new \PHPSandbox\PHPSandbox();
            $manager = new \Pagon\ChildProcess();
            $manager->listen();
            $out = "";
            $child = $manager->parallel(function($p){
                try{
                    $p->on("message", function($ch){
                        echo "Got chunk...\n";
                    });
                    $p->on("exit", function(){
                        echo "Child is exiting...\n";
                    });
                    $p->listen();
                    echo "Meep!\n";
                    ob_start();
                    echo "o.o!\n";
                    $res = ob_get_contents();
                    ob_end_clean();
                    $p->send($res);
                } catch(\Exception $e) {
                    echo "Child exception\n";
                }
                sleep(2);
            }, false);
            $child->on("message", function($ch) use($out){
                echo "Got message; $ch\n";
                $out .= $ch;
            });
            $child->on("exit", function(){
                echo "Exiting...\n";
            });
            $child->on("listen", function(){
                echo "\$child: listen\n";
            });
            #$child->listen();
            $child->wait();
            return $res->end($out);
        } catch(\Exception $e) {
            return $res->end("ERROR: ".$e->getMessage());
        }*/

        try {
            // Run Yii
            ob_start();
            $res->header("Content-type: text/html");

            $fname = $_SERVER["SCRIPT_FILENAME"];
            if(file_exists($fname) && is_file($fname)) {
                $fnp = explode(".", $fname);
                $ext = array_pop($fnp);
                if($ext == "php") {
                    require($fname);
                } else {
                    echo file_get_contents($fname);
                }
            } else {
                # Hotfix
                $_SERVER["SCRIPT_NAME"]="/app.php";
                $_SERVER["DOCUMENT_URI"]="/app.php";
                $_SERVER["SCRIPT_FILENAME"]=realpath($config["base"]."/app.php");
                // change the following paths if necessary
                $yii_config=$config["base"].'/protected/config/main.php';
                // remove the following line when in production mode
                defined('YII_DEBUG') or define('YII_DEBUG',true);
                $c=require_once($yii_config);
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

        return $res->end($o_res);
    }

    static function stop() {
        echo "Baibai...\n";
        posix_kill(getmypid(), SIGUSR1);
    }
}
