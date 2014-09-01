<?php
error_reporting(E_ALL);
$main = "../../../..";

// Yii
include_once("$main/php_modules/yii/framework/yii.php");
$config = include_once("$main/config/yii.php");
Yii::createWebApplication($config);

// WS
include_once("$main/php_modules/WingStyle/WingStyle.php");
ws_copyright();
WS()->load("transition", "position", "float", "border");

// Helper function
function reactToAll($elem) {
    return array(
        $elem.":hover",
        $elem.":active",
        $elem.":focus",
        $elem
    );
}
function cdnRequire($path) {
    echo WS()->import(Yii::app()->cdn->getBaseUrl() . $path)."\n";
}

// CSS includes
cdnRequire("/css/normalize.css");
cdnRequire("/bootstrap/css/bootstrap.min.css");
cdnRequire("/font-awesome/css/font-awesome.min.css");
cdnRequire("/pick-a-color/css/pick-a-color-1.2.3.min.css");

// Variables
$base = Yii::app()->theme->getBaseUrl();

// Panel styles
include_once "panels.ws";

/**
 * Dragon's Inn: Main design.
 * This covers the Menu, Content and Footer.
 */
$menu_height=40;

WS("body")
    ->background->url($base."/images/bg.jpg") // $config['components']['cdn']['baseUrl']
    ->color(white)
->end;

WS("#menu")
    ->position(absolute)
    ->left(0)
    ->right(0)
    ->top(0)
    ->height($menu_height)
    ->width("100%")
    ->background->rgba(0,0,0, 0.4)
->end;

WS("#menu div.left")
    ->width("45%")
    ->float(left)
->end;
WS("#menu div.right")
    ->width("45%")
    ->float(right)
->end;
WS("#menu div.center")
    ->width("10%")
    ->float(left)
    ->text->align(center) # THAT ACTUALLY CENTERS A IMG? O.o
->end;

WS("#menu div img.icon")
    ->height($menu_height)
->end;

WS("#menu div div.text-right")
    ->text->align(left)
    ->float(left)
->end;
WS("#menu div div.text-left")
    ->text->align(right)
    ->float(right)
->end;

// This requires math.
$fsize = (int)floor($menu_height/2-$menu_height/8);
$marg  = (int)floor(($menu_height-$fsize)/2);
WS("#menu div div.text-right", "#menu div div.text-left")
    ->font->size($fsize)
    ->margin->top($marg)
->end;

// This fixes some odd padding behavior and adresses flaoting divs.
WS("#outerContent")
    ->width("70%")
    ->margin->left(auto)
    ->margin->right(auto)
    ->margin->top(100)
->end;

WS("#content")
    ->width("100%")
    ->background->rgba(0,0,0, 0.7)
    ->margin->bottom(10) # Fix for overflow-x+box-shadow
    ->padding->left(10)
    ->padding->right(10)
    ->padding->top(5)
    ->padding->bottom(5)
    ->float(left)
->end;

WS("#Pright div", "#Pleft div", "#Ptop div", "#Pbottom div")
    ->padding(10, 10, 10, 10)
->end;

// Bootstrap fixes
WS(".form-control")
    ->background->color(black)
    ->color(white)
->end;

// Elements
WS("a")
    ->color(lime)
->end;
WS("a:hover")
    ->color("#00DF00")
    ->text->decoration(none)
->end;

// Helper classes
WS(reactToAll(".white-box"))
    ->boxShadow(0, 0, 20, 2, white)
    ->borderRadius(10)
    ->background->color(black)
    ->color(white)
->end;

WS(".white-button")
    ->borderRadius(10)
    ->border(2, solid, white)
->end;

?>

/* Media Queries */
/* Smartphones (portrait and landscape) ----------- */
@media only screen
    and (min-device-width : 320px)
    and (max-device-width : 480px) { <?php

?> }

/* Smartphones (landscape) ----------- */
@media only screen
    and (min-width : 321px) { <?php

?> }

/* Smartphones (portrait) ----------- */
@media only screen
    and (max-width : 320px) { <?php

?> }

/* iPads (portrait and landscape) ----------- */
@media only screen
    and (min-device-width : 768px)
    and (max-device-width : 1024px) { <?php

?> }

/* iPads (landscape) ----------- */
@media only screen
    and (min-device-width : 768px)
    and (max-device-width : 1024px)
    and (orientation : landscape) { <?php

?> }

/* iPads (portrait) ----------- */
@media only screen
    and (min-device-width : 768px)
    and (max-device-width : 1024px)
    and (orientation : portrait) { <?php

?> }

/* Desktops and laptops ----------- */
@media only screen
    and (min-width : 1224px) { <?php

?> }

/* Large screens ----------- */
@media only screen
    and (min-width : 1824px) { <?php

?> }

/* iPhone 4 ----------- */
@media
    only screen and (-webkit-min-device-pixel-ratio : 1.5),
    only screen and (min-device-pixel-ratio : 1.5) { <?php

?> }
