<?php namespace BIRD3\Foundation;

use Illuminate\Routing\Controller;
use BIRD3\Support\GlobalConfig;
use BIRD3\Support\Compatibility;

use Cache;

// I need...a base controller. o.o;
abstract class BaseController {
	public $layout='@theme/Layouts/main ';
	public $breadcrumbs=array();
	public $showingBan=false;

	// Style stuff...
	public $og_type="website";
	public $og_image;
	public $panelBottom=false;
	public $tabbar;
	public $leftSide;
	public $rightSide;

	// Set to true to get ALL the page. At least anything between menubar and bottom.
	// Disables banner and footer.
	public $allPage=false;

	// This shows the introduction section.
	public $isIndex=false;

	// Used in the design
	public $bg_class="onAll";

	// Determine which module should be loaded in JavaScript.
	public $module = "main";

	// Menu
	public $navEntries = array(
		"Dragon's Inn"=>array(
			"href"=>"#",
			"entries"=>array(
				array("Home", "icon"=>"fa fa-home", "url"=>"/"),
				array("Rules & TOS", "icon"=>"fa fa-legal", "url"=>array("/docs/Rules_and_TOS")),
				array(
					"Roleplaying etiquette",
					"icon"=>"fa fa-info-circle",
					"url"=>array("/docs/Roleplaying_Etiquette")
				),
				array(
					"Staff",
					"icon"=>"glyphicon glyphicon-certificate",
					"url"=>array("/home/staff")
				),
			)
		),
		"Community"=>array(
			"href"=>"#",
			"icon"=>"fa fa-users",
			"entries"=>array(
				array("Users", "icon"=>"fa fa-users","url"=>array("/user/list")),
				array("Chat <font color=\"lime\">NN</font>",
					"url"=>array("/chat"),
					"icon"=>"fa fa-comments"
				),
				array("Forum", "icon"=>"fa fa-comment","url"=>array("/form")),
				array("Blogs", "icon"=>"glyphicon glyphicon-list-alt","url"=>array("/blog"))
			)
		),
		"Story"=>array(
			"href"=>"#",
			"icon"=>"fa fa-globe",
			"entries"=>array(
				array("Storyline", "icon"=>"fa fa-file-text","url"=>array("/hotel/story")),
				array("Places", "icon"=>"fa fa-compass","url"=>array("/hotel/places")),
				array("Jobs", "icon"=>"fa fa-building","url"=>array("/hotel/jobs"))
			),
		),
		"Characters"=>array(
			"href"=>"#",
			"icon"=>"fa fa-book",
			"entries"=>array(
				array("Latest", "icon"=>"fa fa-list","url"=>array("/chars/latest")),
				array("All", "icon"=>"fa fa-database","url"=>array("/chars/all")),
				array(
					"Families &amp; Clans",
					"icon"=>"fa fa-child",
					"url"=>array("/chars/associations")
				),
			),
		),
		"Media"=>array(
			"href"=>"#",
			"icon"=>"glyphicon glyphicon-eye-open",
			"entries"=>array(
				array("Latest", "icon"=>"fa fa-list","url"=>array("/media/all/latest")),
				array("All", "icon"=>"fa fa-folder","url"=>array("/media/all/list")),
				array("Art", "icon"=>"fa fa-paint-brush","url"=>array("/media/art")),
				array("Music","icon"=>"glyphicon glyphicon-headphones","url"=>array("/media/audio")),
				array("Essay", "icon"=>"glyphicon glyphicon-bookmark","url"=>array("/media/story"))
			)
		),
	);

	public function __construct() {
		#global $res;
		#$res->header("Pragma: no-cache");
		#$res->header("Cache-Control: max-age=0");
		#$this->og_image = Yii::app()->cdn->baseUrl."/theme/images/sign.png";

		/*if(Yii::app()->user->isVIP()) {
			$this->navEntries["Dragon's Inn"]["entries"][] = array(
				"Manage",
				"icon"=>"fa fa-cogs",
				"url"=>array("/home/manage")
			);
		}
		// Kinda redundant, but easier to read.
		if(Ban::isBanned($_SERVER['REMOTE_ADDR'], Ban::BY_IP)) {
			return $this->redirect("/banned");
		} else if(
			!Yii::app()->user->isGuest
			&& Ban::isBanned(Yii::app()->user->id, Ban::BY_USER)
		) {
			return $this->redirect("/banned");
		}*/

		if(isset($_GET['ajax']) && $_GET['via']=="bird3") {
			// There is an action that we, and no other service, wants.
			$msg=["status"=>"ok","error"=>"none"];
			switch($_GET['action']) {
				case "user:update_visit":
					/*if(!Yii::app()->user->isGuest) {
						// Long statement made easy thanks to Duder.
						User::me()->lastvisit_at = time();
						if(!User::me()->update()) {
							$msg["status"] = "failed";
							$msg["error"] = "Failed to update DB record.";
						}
					}*/
				break;
			}
			echo json_encode($msg);
		}
	}

	// TODO: Make it work in L5
	public function registerScripts() {
		$cs = Yii::app()->clientScript;

		$cdnUrl = GlobalConfig::get("CDN.baseurl");
		$url = config("app.url");
		$escYiiUrl = json_encode($url);

		// Load webpack stuff
		global $opt; // FIXME: Globally access hprose stuff.
		$hash = $opt["userData"]["webpackHash"];
		$bower = resolve("@bower");
		$cdnApp = GlobalConfig::get("CDN.baseUrl")."/app";
		$escCdnApp = json_encode($cdnApp);
		$use = json_encode($this->panelBottom);

		// Load the loader
		$ljs = ""; $key = "include.js";
		$ljs = Cache::get($key, function(){
			return file_get_contents("$bower/scriptinclude/include.min.js");
		});

		// Register scripts
		// # Google Analytics
		$cs->registerScript("google.analytics.js","/* Google */
			// Google Analytics
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
			ga('create', 'UA-58656116-1', 'auto');
			ga('send', 'pageview');
		/* Google end */", CClientScript::POS_HEAD);

		// # BIRD3 JavaScript. Yes, it's a view. xD
		$cs->registerScript("bird3.js", View::makePartial(
			resolve("@theme/Templates/InlineJavaScript"), [
				"hash"=>$hash,
				"module"=>$this->module,
				"use"=>$use,
				"escYiiUrl"=>$escYiiUrl,
				"escCdnApp"=>$escCdnApp
			], true
		));

		// The design.
		$cs->registerCssFile("$cdnApp/$hash-libwebpack.css");

		// Special script for index.
		$fbAlpha = 0.3;
		if($this->isIndex && Yii::app()->user->isGuest) {
			if(Compatibility::check("blur_bg")) {
				// Do the blur! hur hur hur.
				// FIXME: Did I spell "blur" right? o.o
				$cs->registerScript("fracs+intro",
					"window.addEventListener('scroll', function(){
						$('#blurr-bg').css({opacity: (1-$('#intro').visibility())});
					});",
				CClientScript::POS_END);
			} else {
				// Since the blur effect would cause issues, let's just darken the BG more.
				$cs->registerScript("fracs+intro",
					"window.addEventListener('scroll', function(){
						$('#outerContent').css({
							background: 'rgba(0,0,0,'+(
								$fbAlpha-($fbAlpha*$('#intro').visibility())
							)+')'
						});
					});",
				CClientScript::POS_END);
			}
		}

		// For browsers that are NOT compatible with the blurr effect
		if(!Compatibility::check("blur_bg")) {
			// This will cause an rgba() overlay, keeping the blurr hidden.
			$this->bg_class = "fallback";
			$cs->registerCss("blur-fallback","#outerContent {
				background: rgba(0,0,0, $fbAlpha);
			}");
		}

		// Mobile browsers cant center the bg properly. So we need to use "scroll".
		$browser = Yii::app()->browser;
		if(
		    $browser->getBrowser() == Browser::BROWSER_IPHONE
		    || $browser->getBrowser() == Browser::BROWSER_IPAD
		    || $browser->getBrowser() == Browser::BROWSER_ANDROID
		) {
			$cs->registerCss("mobile-bg-fix", "/* Mobile browser fixture... */
				#blurr-bg, #bg {
				    /* Makes the backround fixed on mobile devices... wut? Logic? >.> */
				    background-attachment: scroll;
				}
			/* End fixture */");
		}
	}
}
