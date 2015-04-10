<?php
error_reporting(E_ALL);
date_default_timezone_set("UTC");
$main = dirname(__FILE__)."/../../..";

// Yii
require_once("$main/php_modules/autoload.php");
$config = require_once("$main/protected/config/main.php");
Yii::createWebApplication($config);
require_once("$main/protected/components/Controller.php");

// Cause...yii.
#header_remove("Pragma");

// Cache ourselves. Trick borrowed: http://css-tricks.com/snippets/php/intelligent-php-cache-control/
$lastModified=filemtime(__FILE__);
$etagFile = md5_file(__FILE__);
// Obtain headers
$ifModifiedSince=(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false);
$etagHeader=(isset($_SERVER['HTTP_IF_NONE-MATCH']) ? $_SERVER['HTTP_IF_NONE-MATCH'] : false);
// Send
HttpResponse::header("X-WingStyle: Alive");
HttpResponse::header("Cache-control: public, max-age=604800");
HttpResponse::header("Last-Modified: ".gmdate("D, d M Y H:i:s", $lastModified)." GMT");
HttpResponse::header("Etag: $etagFile");
HttpResponse::header("Content-type: text/css");

//check if page has changed. If not, send 304 and exit
if($etagHeader && $etagHeader == $etagFile) {
    HttpResponse::status(304);
    exit;
}

// Internal cache. Hacking Yii to do things...ahh...poor thing. :)
$key = "ws-$etagFile";
$c = Yii::app()->controller = new Controller("WingStyle");
if($c->beginCache($key)) {
// This is pretty much the else condition.

// WingStyle
define("WS_NO_HEADER", 1);
include_once("$main/php_modules_ext/WingStyle/WingStyle.php");

// Helper function
function reactToAll($elem) {
    return array(
        $elem.":hover",
        $elem.":active",
        $elem.":focus",
        $elem
    );
}
#WS()->beauty = false;
WS()->load(
    "transition", "position", "float", "border",
    "whiteSpace", "wordWrap", "display"
);


// Variables
$base = Yii::app()->cdn->baseUrl."/theme";
$img = Yii::app()->cdn->baseUrl."/images";

// Panel styles
include "panels.ws";

/**
 * Dragon's Inn: Main design.
 * This covers the Menu, Content and Footer.
 */
$menu_height=40;
$bgStr = "url(\"$base/images/bg.jpg\") no-repeat center center";
$blurStr = "url(\"$base/images/blur.png\") no-repeat center center";


WS(body)
    # Fallback for image loading
    ->background->color(black)
    ->color(white)
    ->height("100%")
    ->width("100%")
    ->zIndex(0)
    ->position(relative)
->end;
WS("#blurr-bg", "#bg")
    ->background->url("$base/images/bg.jpg")
    ->backgroundPosition("center center")
    ->backgroundRepeat("no-repeat")
    ->backgroundAttachment("fixed")
    #->height("100%")
    ->position(fixed)
    ->top(0)
    ->left(0)
    ->right(0)
    ->bottom(0)
->end;
WS("#bg")
    ->zIndex(-2)
->end;
WS("#blurr-bg")
    ->zIndex(-1)
    ->blur(5)
->end;
WS("#blurr-bg.onIndex")
    ->opacity(0)
->end;
WS("#blurr-bg.onAll")
    ->opacity(1)
->end;
WS("#blurr-bg.fallback")
    ->opacity(0)
->end;

// To fix some bg related issues and such...
/*
WS(body)
    ->overflow(hidden)
->end;
WS("#app")
    ->height("100%")
    ->width("100%")
    ->overflow(auto)
    ->addTxt("-webkit-overflow-scrolling: touch;")
    ->margin(0)
    ->padding(0)
->end;
*/

WS("#banner")
    ->height(100)
    ->background->url($base."/images/banner.jpg")
    ->backgroundPosition("center center")
    ->backgroundRepeat("no-repeat")
    ->width("100%")
->end;

WS("#browser_error")
    ->background->rgba(204,0,0, 0.6)
    ->color(white)
    ->padding->left("2%")
    ->padding->right("2%")
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

$in_marg = 80;
WS("#intro")
    ->padding->top($in_marg)
    ->padding->bottom($in_marg)
    ->background->rgba(0,0,0, 0.25)
    ->width("100%")
->end;

// This fixes some odd padding behavior and adresses flaoting divs.
WS("#outerContent")
    ->zIndex(3)
->end;
WS(".AllYourPageAreBelongToUs")
    ->background("none !important")
->end;
WS(".normalPage")
    ->margin->top("4%")
->end;
WS(".normalPage-tabbed")
    ->margin->top("2%")
->end;

WS("#tabbar")
    ->margin->bottom(50)
    ->margin->left(auto)
    ->margin->right(auto)
    ->display("block !important")
    ->position(relative)
    ->width("75%")
->end;

WS("#content")
    # Responsible design implementation: http://stackoverflow.com/a/25634192/2423150
    ->padding->left("2%")
    ->padding->right("2%")
    ->padding->top(5)
    ->padding->bottom(5)
    ->float(left)
    ->position(relative)
    ->background->rgba(0,0,0, 0.6)
    ->width("100%")
->end;

WS("#footer")
    # Fix and clear the mess from above.
    ->display(block)
    ->position(relative)
    # Now the style.
    ->text->align(center)
    ->background->rgba(0,0,0,0.4)
    ->padding->top(7)
    ->padding->left(2)
    ->padding->right(2)
    ->padding->bottom(10)
    ->width("100%")
->end;

WS("#leftSide", "#rightSide")
    ->background->rgba(0,0,0, 0.8)
    ->width("19%")
    ->margin->top(20)
    ->float(left)
    ->border(1, solid, white)
    ->padding->left(3)
    ->padding->right(3)
    ->borderRadius(10)
->end;
WS("#leftSide")
    ->margin->right("1%")
->end;
WS("#rightSide")
    ->margin->left("1%")
->end;

WS("#leftSide", "#rightSide", "#content")
    ->whiteSpace(normal)
    ->wordWrap(breakWord)
->end;

WS("#Pright", "#Pleft", "#Pbottom")
    ->padding(5, 5, 5, 5)
->end;

// Few customs
WS("#Ptop > div")
    ->display(none)
->end;

// Helper classes
WS(array_merge(reactToAll(".white-box"), [".just-white-box"]))
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

WS("#trigger-tabs")
    ->float(right)
->end;

// jQuery.mmenu
WS(".mm-menu")
    ->width("20%")
->end;

// Icons
WS(".iconblock")
    ->display("inline-block")
    ->backgroundSize(auto, 15)
    ->backgroundPosition("center center")
    ->backgroundRepeat("no-repeat")
    ->height(15)
    ->width(21)
->end;
WS(".iconblock.psn")
    ->backgroundImage("url(\"$img/brands/playstation.png\")")
->end;
WS(".iconblock.xbl")
    ->backgroundImage("url(\"$img/brands/xbox_360.png\")")
->end;
WS(".iconblock.sofurry")
    ->backgroundImage("url(\"$img/brands/sofurry.png\")")
->end;

// Bootstrap fixes
WS("code", "pre")
    ->color(white)
    ->border(none)
->end;
WS("code", "pre", ".hljs")
    ->background->rgba(31, 31, 31, 0.7)
->end;
WS("pre")
    ->padding("0.5em")
->end;
WS("blockquote")
    ->background->rgba(0,0,0,0.7)
->end;
WS(".well", ".list-group-item", ".panel")
    ->background->rgba(21,21,21, 0.6)
->end;
WS(".well")
    ->padding(round(19/2))
->end;
WS(".panel-primary > .panel-heading")
    ->background->rgba(42,159,214, 0.6)
->end;

// Bootstrap dialog
WS(".bootstrap-dialog .modal-dialog")
    ->margin->top("7%")
->end;
WS(".bootstrap-dialog.type-default .modal-header")
    ->background("#282828")
->end;
WS(".bootstrap-dialog.type-default .bootstrap-dialog-title")
    ->color(white)
->end;
WS(".bootstrap-dialog.type-info .modal-header")
    ->background("#9933cc")
->end;
WS(".bootstrap-dialog.type-primary .modal-header")
    ->background("#2a9fd6")
->end;
WS(".bootstrap-dialog.type-success .modal-header")
    ->background("#77b300")
->end;
WS(".bootstrap-dialog.type-warning .modal-header")
    ->background("#ff8800")
->end;
WS(".bootstrap-dialog.type-danger .modal-header")
    ->background("#cc0000")
->end;

// highlight.js compatible code blocks.
WS(".hascode")
    ->border("none !important")
    ->margin("0 !important")
->end;
WS("pre.hascode")
    ->padding("0 !important")
->end;
WS("pre.nocode")
    ->border(1, solid, white)
->end;

# Avatar
WS(".thumbnail.avatar")
    ->height(100)
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
    WS("#tabbar")
        ->width("90%")
        ->margin->top(10)
        ->margin->bottom(0)
    ->end;
    WS(".tabs-multi > .nav-tabs > li > a")
        ->padding(2,2)
    ->end;
?> }

/*
    DISPLAY TYPES
    Following media queries set up the show-onXXX components.
*/
@media only screen and (min-width: 401px) { <?php
    WS(".tabs-multi > .nav-tabs > li > a")
        ->padding(5,5)
    ->end;
?> }
@media only screen and (min-width:500px) { <?php
    WS(".tabs-multi > .nav-tabs > li > a")
        ->padding(10,10)
    ->end;
?> }

@media only screen and (min-width:885px) { <?php
    WS(".tabs-multi > .nav-tabs > li > a")
        ->padding(15,15)
    ->end;
?> }

/* Special query for the background... */
@media only screen and (min-width:1920px) {
    body {
        background-size: 100% 100%;
    }
}

<?php
} // End of $c->beginCache($key)
?>

/* Browser specific fixes */
<?php $browser = Yii::app()->browser; ?>
<?php if(
    $browser->getBrowser() == Browser::BROWSER_IPHONE
    || $browser->getBrowser() == Browser::BROWSER_IPAD
    || $browser->getBrowser() == Browser::BROWSER_ANDROID
): ?>
#blurr-bg, #bg {
    /* Makes the backround fixed on mobile devices... wut? Logic? >.> */
    background-attachment: scroll;
}
<?php endif; ?>
