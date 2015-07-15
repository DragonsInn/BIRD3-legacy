<?php

require_once __DIR__."/../php_modules/autoload.php";

// Output stuff
use Colors\Color;
$c = new Color();

// Config stuff
$BIRD3 = parse_ini_file(__DIR__."/../config/BIRD3.ini", true);
$dsn = 'mysql:host=localhost;dbname='.$BIRD3['DB']['mydb'];
$user = $BIRD3["DB"]["user"];
$pass = $BIRD3["DB"]["pass"];

echo SqlFormatter::format($argv[1]);

$db = new PDO($dsn, $user, $pass);
$count = $db->exec($argv[1]);

echo PHP_EOL;
echo $c("Affected:")->green()." ".$count.PHP_EOL;
