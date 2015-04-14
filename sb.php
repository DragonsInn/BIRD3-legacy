<?php

require_once "php_modules/autoload.php";

function test($string){
    return 'Hello ' . $string;
}

$sandbox = new PHPSandbox\PHPSandbox;
$sandbox->set_option('validate_functions', false);
$sandbox->validate_keywords=false;
$sandbox->allow_classes=true;
$sandbox->whitelist_func('*');
$sandbox->define_func("header", function($ec) {
    echo "Header: $ec\n";
});
$result = $sandbox->execute(function(){
    header("Foo: bar");
    return test('world');
});
