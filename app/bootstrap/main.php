<?php

/*
    Kicks in the app.

    In this case, this one will return an instance of BIRD3\Foundation\WebApplication
*/

$php = __DIR__."/php";
require_once("$php/autoload.php");
return require_once("$php/app.php");
