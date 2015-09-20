<?php
error_reporting(E_ALL);
ini_set("display_errors","0");

// Stupid time zone .... >.>
date_default_timezone_set("UTC");

// Configure globals
$sg = [
    '_SERVER', '_GET', '_POST',
    '_FILES', '_COOKIE', '_REQUEST',
    '_SESSION'
];
foreach($sg as $g) {
    if(isset($GLOBALS[$g]) && !is_array($GLOBALS[$g])) {
        $GLOBALS[$g]=array();
    }
}
$_REQUEST=array_merge($_GET, $_POST);
$_ENV=array_merge($_ENV, $_SERVER);
