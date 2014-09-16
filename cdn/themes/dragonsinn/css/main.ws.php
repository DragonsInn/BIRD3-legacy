<?php
error_reporting(E_ALL);
$main = dirname(__FILE__)."/../../../..";

// Yii
#define("YII_DEBUG", true);
include_once("$main/php_modules/yii/framework/yii.php");
$config = include_once("$main/protected/config/main.php");
Yii::createWebApplication($config);

// WS
include_once("$main/php_modules/WingStyle/WingStyle.php");
ws_copyright();
WS()->load(
    "transition", "position", "float", "border",
    "whiteSpace", "wordWrap", "display"
);

// Helper function
function reactToAll($elem) {
    return array(
        $elem.":hover",
        $elem.":active",
        $elem.":focus",
        $elem
    );
}

// Variables
$base = Yii::app()->theme->baseUrl;

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
    ->width("85%")
    ->margin->left(auto)
    ->margin->right(auto)
    ->whiteSpace(nowrap)
    ->display(block)
->end;
WS(".extraMargin")
    ->margin->top(170)
->end;
WS(".no-extraMargin")
    ->margin->top(70)
->end;

WS("#outerContent > div")
    ->display("inline-block")
->end;

WS("#tabbar")
    ->margin->bottom(30)
    ->margin->top(100)
    ->margin->left(auto)
    ->margin->right(auto)
    ->display(block)
    ->position(relative)
    ->width("75%")
    ->color(white)
    ->background->rgba(0,0,0, 0.3)
->end;

WS("#content")
    ->background->rgba(0,0,0, 0.7)
    # Fix for overflow-x+box-shadow
    ->margin->bottom(10)
    # Responsible design implementation: http://stackoverflow.com/a/25634192/2423150
    ->padding->left("1%")
    ->padding->right("1%")
    ->padding->top(5)
    ->padding->bottom(5)
    ->float(left)
    ->border(1, solid, white)
->end;
WS(".cType1")
    # Type 1: Full width
    ->width("100%")
->end;
WS(".cType2")
    # Type 2: One sidebar shown
    ->width("80%")
->end;
WS(".cType3")
    # Type 3: Both sidebars shown
    ->width("60%")
->end;

WS("#leftSide", "#rightSide")
    ->background->rgba(0,0,0, 0.8)
    ->width("18%")
    ->margin->top(20)
    ->float(left)
    ->border(1, solid, white)
    ->padding->left(3)
    ->padding->right(3)
    ->borderRadius(10)
->end;
WS("#leftSide")
    ->margin->right("2%")
->end;
WS("#rightSide")
    ->margin->left("2%")
->end;

WS("#leftSide", "#rightSide", "#content")
    ->whiteSpace(normal)
    ->wordWrap(breakWord)
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
