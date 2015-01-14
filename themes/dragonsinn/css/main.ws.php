<?php
error_reporting(E_ALL);
$main = dirname(__FILE__)."/../../..";

// Yii
include_once("$main/php_modules/yii/framework/yii.php");
$config = include_once("$main/protected/config/main.php");
Yii::createWebApplication($config);

// Cause...yii.
header_remove("Pragma");

// Cache ourselves. Trick borrowed: http://css-tricks.com/snippets/php/intelligent-php-cache-control/
$lastModified=filemtime(__FILE__);
$etagFile = md5_file(__FILE__);
// Obtain headers
$ifModifiedSince=(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false);
$etagHeader=(isset($_SERVER['HTTP_IF_NONE-MATCH']) ? $_SERVER['HTTP_IF_NONE-MATCH'] : false);
// Send
header("X-WingStyle: Alive");
header("Cache-control: public, max-age=604800");
header("Last-Modified: ".gmdate("D, d M Y H:i:s", $lastModified)." GMT");
header("Etag: $etagFile");
header('Cache-Control: public');
//check if page has changed. If not, send 304 and exit
if($etagHeader && $etagHeader == $etagFile) {
    header("HTTP/1.1 304 Not Modified");
    exit;
}
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
$base = Yii::app()->cdn->baseUrl."/theme";
$img = Yii::app()->cdn->baseUrl."/images";

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
    ->height("100%")
    ->width("100%")
    # Not implemented, yet.
    ->backgroundRepeat("no-repeat")
    ->backgroundPosition("50% 50%")
    ->backgroundAttachment(fixed)
->end;

WS("#menu")
    ->height->min($menu_height)
    ->width("100%")
    ->background->rgba(0,0,0, 0.4)
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
WS(".AllYourPageAreBelongToUs")
    ->width("100%")
    ->margin(0)
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
->end;

WS("#content")
    ->background->rgba(0,0,0, 0.5)
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
WS("#footer")
    # Fix and clear the mess from above.
    ->display(block)
    ->position(relative)
    ->clear("both")
    # Now the style.
    ->border(1, solid, white)
    ->borderRadius(5)
    ->text->align(center)
    ->background->rgba(0,0,0,0.3)
    #->background(orange)
    ->padding(5,2.5,5,2.5)
    ->margin->left(auto)
    ->margin->right(auto)
    ->margin->top(50)
    ->minWidth(200)
    ->maxWidth(800)
    ->width("30%")
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

WS("#Pright", "#Pleft", "#Pbottom")
    ->padding(10, 10, 10, 10)
->end;

// Few customs
#WS("#Ptop")
#    ->border->bottom(1, solid, white)
#->end;
WS("#Ptop > div")
    ->display(none)
->end;


// Elements
/*WS("#outerContent * a")
    ->color(lime)
->end;
WS("#outerContent * a:hover")
    ->color("#00DF00")
    ->text->decoration(none)
->end;
*/

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

// Its an input, voerride a lot of things.
WS("#allSearch")
    ->margin->top(-$marg/2)
    ->height($fsize*2)
    ->background(black)
    ->padding->left(5)
    ->padding->right(5)
    ->border(1)
->end;

WS(".circle")
    ->borderRadius("50%")
    ->display("inline-block")
    ->text->align(center)
->end;
WS(".circle.circle-small")
    ->padding->top(3)
    ->font->size(20)
    ->border(2, solid, white)
->end;
WS(".circle.circle-icon")
    ->padding(0, 0, 0, 0)
    ->padding->top(10)
    ->font->size(40)
    ->width(80)
    ->height(80)
    ->border(2, solid, white)
->end;

WS(".tabs-multi ul")
    ->display(inlineBlock)
->end;
WS(".tabs-multi > .nav-tabs")
    ->border->color("transparent")
->end;
WS(
    ".tabs-multi > .nav-tabs > .active > a",
    ".tabs-multi > .nav-tabs > .active > a:hover",
    ".tabs-multi > .nav-tabs > .active > a:focus"
)
    ->background("transparent")
->end;
WS(".tabs-multi > .nav-tabs > li > a")
    ->margin(0)
->end;

WS(reactToAll(".linkBubble"))
    ->display(inlineBlock)
    ->float(left)
    ->margin->top(5)
    ->margin->left(5)
    ->margin->right(5)
    ->padding(5,10,5,10)
    ->color(white)
    ->borderRadius(10)
->end;
WS(".linkBubble:hover")
    ->background->rgba(0,128,0,0.4)
->end;


WS("#trigger-tabs")
    ->float(right)
->end;

// Optics
$mtabs = "#menu-tabs >";
$onMini = "$mtabs .show-onMini, .show-onMini";
$onMedium = "$mtabs .show-onMedium, .show-onMedium";
$onLarge = "$mtabs .show-onLarge, .show-onLarge";
$onBig = "$mtabs .show-onBig, .show-onBig";
$onArray=array($onMini, $onMedium, $onLarge, $onBig);
WS($onArray)
    ->display(none)
->end;
// We start with the smallest, so this is always true.
WS($onMini)
    ->display(block)
->end;

WS(".linkBubble")
    ->margin->right(2)
    ->margin->left(2)
->end;
WS(".linkBubble div")
    ->display(inlineBlock)
    ->float(left)
->end;
WS(".linkBubble .circle")
    ->height(10)
    ->width(10)
    ->margin->right(5)
->end;
WS(".linkBubble div", ".linkBubble .circle")
    ->font->size(15)
    ->padding(0)
->end;
WS(".mm-menu")
    ->width("20%")
->end;
WS(".iconblock")
    # text-align: center; display: inline-block; width: 15px;
    ->textAlign(center)
    ->display("inline-block")
    ->width(15)
->end;

// Icons
WS(".psn")
    ->background->url("$img/brands/playstation.png")
    ->display("inline-block")
    ->backgroundSize(15, 15)
    ->backgroundRepeat("no-repeat")
    ->height(15)
    ->width(15)
->end;
WS(".xbl")
    ->background->url("$img/brands/xbox_360.png")
    ->display("inline-block")
    ->backgroundSize(15, 15)
    ->backgroundRepeat("no-repeat")
    ->height(15)
    ->width(15)
->end;
?>

/* Media Queries */
/* Goal is to stage the view:
    On smartphones:
        - No sidebars, move them to the right side, somehow.
        - Tabbar and top menu are closer together.
        - Site background varies
*/

/*
    SCREEN COMPONENTS
    The following queries are for the screen components:

    - Content
    - Tabbar
*/
@media only screen and (max-device-width: 760px) { <?php
    WS("#outerContent")
        ->width("98%")
        ->background(orange)
    ->end;
    WS("#tabbar")
        ->width("90%")
        ->margin->top(10)
        ->margin->bottom(0)
    ->end;
    WS(".extraMargin")
        ->margin->top(45)
    ->end;
    WS(".no-extraMargin")
        ->margin->top(25)
    ->end;
    WS(".tabs-multi > .nav-tabs > li > a")
        ->padding(2,2)
    ->end;
    WS(".circle.circle-small")
        ->width(25)
        ->height(25)
        ->font->size(10)
    ->end;
    WS(".circle")
        ->border(none)
    ->end;
?> }

/*
    DISPLAY TYPES
    Following media queries set up the show-onXXX components.
*/
@media only screen and (min-width: 401px) { <?php
    WS($onMini)
        ->display(none)
    ->end;
    WS($onMedium)
        ->display(block)
    ->end;
    WS(".tabs-multi > .nav-tabs > li > a")
        ->padding(5,5)
    ->end;
    WS(".circle.circle-small")
        ->width(50)
        ->height(50)
        ->font->size(25)
    ->end;
?> }
@media only screen and (min-width:500px) { <?php
    WS($onMedium)
        ->display(none)
    ->end;
    WS($onLarge)
        ->display(block)
    ->end;
    WS(".tabs-multi > .nav-tabs > li > a")
        ->padding(10,10)
    ->end;
    WS(".circle.circle-small")
        ->width(40)
        ->height(40)
        ->font->size(20)
    ->end;

    WS(".linkBubble .circle", ".tsection")
        ->float(none)
    ->end;
    WS(".tsection")
        ->display(block)
    ->end;
    WS(".linkBubble")
        ->margin->right(5)
        ->margin->left(5)
    ->end;
    WS(".linkBubble .circle")
        ->height(50)
        ->width(50)
        ->margin->right(10)
        ->font->size(30)
        ->display(block)
    ->end;
    WS(".linkBubble .tsection")
        ->font->size(15)
    ->end;
?> }

@media only screen and (min-width:885px) { <?php
    WS($onLarge)
        ->display(none)
    ->end;
    WS($onBig)
        ->display(block)
    ->end;
    WS(".tabs-multi > .nav-tabs > li > a")
        ->padding(15,15)
    ->end;
    WS(".circle.circle-small")
        ->width(50)
        ->height(50)
        ->font->size(25)
    ->end;
    WS(".tsection")
        ->width("90%")
    ->end;
    WS(".circle.circle-icon")
        ->padding(0, 0, 0, 0)
        ->padding->top(10)
        ->font->size(40)
        ->width(80)
        ->height(80)
        ->border(2, solid, white)
    ->end;
?> }

/* Special query for the background... */
@media only screen and (min-width:1920px) {
    body {
        background-size: 100% 100%;
    }
}
