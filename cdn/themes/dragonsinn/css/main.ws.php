<?php include_once("../../../../php_modules/WingStyle/WingStyle.php");

ws_copyright();

WS("*")
    ->padding(0)
    ->margin(0)
->end;

include_once "panels.ws";

// Design
WS("#content")
    ->width("100%")
    ->height("100%")
->end;
