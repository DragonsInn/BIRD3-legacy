<?php namespace BIRD3\Foundation;

// Laravel
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as LaravelController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

// Vendor
use Ikimea\Browser\Browser;

// BIRD3
use BIRD3\Support\GlobalConfig;
use BIRD3\Support\Compatibility;

// Facades
use Cache;
use View;
use Hprose;
use HTML;
use Request;
use Response;
use FlipFlop;

// I need...a base controller. o.o;
abstract class BaseController extends LaravelController {

    use DispatchesJobs, ValidatesRequests, AuthorizesRequests;

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

    // Used as a transparency value
    private $fbAlpha = 0.3;

    // Scripts
    private $cssTags = [];
    private $jsTags = [
        "top" => [],
        "bottom" => []
    ];

	// Menu
	public $navEntries = array(
		"Dragon's Inn"=>array(
			"href"=>"#",
			"entries"=>array(
				array("Home", "icon"=>"fa fa-home", "url"=>"/"),
				array("Rules & TOS", "icon"=>"fa fa-legal", "url"=>"/docs/Rules_and_TOS"),
				array(
					"Roleplaying etiquette",
					"icon"=>"fa fa-info-circle",
					"url"=>"/docs/Roleplaying_Etiquette"
				),
				array(
					"Staff",
					"icon"=>"glyphicon glyphicon-certificate",
					"url"=>"/home/staff"
				),
			)
		),
		"Community"=>array(
			"href"=>"#",
			"icon"=>"fa fa-users",
			"entries"=>array(
				array("Users", "icon"=>"fa fa-users","url"=>"/user/list"),
				array("Chat <font color=\"lime\">NN</font>",
					"url"=>"/chat",
					"icon"=>"fa fa-comments"
				),
				array("Forum", "icon"=>"fa fa-comment","url"=>"/form"),
				array("Blogs", "icon"=>"glyphicon glyphicon-list-alt","url"=>"/blog")
			)
		),
		"Story"=>array(
			"href"=>"#",
			"icon"=>"fa fa-globe",
			"entries"=>array(
				array("Storyline", "icon"=>"fa fa-file-text","url"=>"/hotel/story"),
				array("Places", "icon"=>"fa fa-compass","url"=>"/hotel/places"),
				array("Jobs", "icon"=>"fa fa-building","url"=>"/hotel/jobs")
			),
		),
		"Characters"=>array(
			"href"=>"#",
			"icon"=>"fa fa-book",
			"entries"=>array(
				array("Latest", "icon"=>"fa fa-list","url"=>"/chars/latest"),
				array("All", "icon"=>"fa fa-database","url"=>"/chars/all"),
				array(
					"Families &amp; Clans",
					"icon"=>"fa fa-child",
					"url"=>"/chars/associations"
				),
			),
		),
		"Media"=>array(
			"href"=>"#",
			"icon"=>"glyphicon glyphicon-eye-open",
			"entries"=>array(
				array("Latest", "icon"=>"fa fa-list","url"=>"/media/all/latest"),
				array("All", "icon"=>"fa fa-folder","url"=>"/media/all/list"),
				array("Art", "icon"=>"fa fa-paint-brush","url"=>"/media/art"),
				array("Music","icon"=>"glyphicon glyphicon-headphones","url"=>"/media/audio"),
				array("Essay", "icon"=>"glyphicon glyphicon-bookmark","url"=>"/media/story")
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

		if(Request::ajax() && Request::get("via")=="bird3") {
			// There is an action that we, and no other service, wants.
			$msg=["status"=>"ok","error"=>"none"];
			switch(Request::get("action")) {
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

	public function makeTopScripts() {
        // Pick up CDN info
		$cdnUrl = GlobalConfig::get("CDN.baseurl");
		$url = config("app.url");
		$escYiiUrl = json_encode($url);

		// Load webpack stuff
		$hash = Hprose::get("wpHash");
		$bower = resolve("@bower");
		$cdnApp = GlobalConfig::get("CDN.baseUrl")."/app";
		$escCdnApp = json_encode($cdnApp);
		$use = json_encode($this->panelBottom);

		// Register scripts
		// # BIRD3 JavaScript. Yes, it's a view. xD
		$inlineScript = $this->renderPartialFromFile(
			resolve("@theme/Templates/InlineJavaScript.php"),
			[
				"hash"=>$hash,
				"module"=>$this->module,
				"use"=>$use,
				"escYiiUrl"=>$escYiiUrl,
				"escCdnApp"=>$escCdnApp
			]
		);
		$this->jsTags["top"][] = '<script type="text/javascript">'.$inlineScript.'</script>';

		// The design.
		$this->cssTags[] = HTML::style("$cdnApp/$hash-libwebpack.css");

		// For browsers that are NOT compatible with the blurr effect
		if(!Compatibility::check("blur_bg")) {
			// This will cause an rgba() overlay, keeping the blurr hidden.
			$this->bg_class = "fallback";
			$style =
            "#outerContent {
				background: rgba(0,0,0, $fbAlpha);
			}";
            $this->cssTags[] = "<style>$style</style>";
		}

		// Mobile browsers cant center the bg properly. So we need to use "scroll".
		$browser = new Browser(Request::server("HTTP_USER_AGENT"));
		if(
		    $browser->getBrowser() == Browser::BROWSER_IPHONE
		    || $browser->getBrowser() == Browser::BROWSER_IPAD
		    || $browser->getBrowser() == Browser::BROWSER_ANDROID
		) {
			// Mobile browser fixture...
			// Makes the backround fixed on mobile devices... wut? Logic? >.>
			$bstyle =
            "#blurr-bg, #bg {
			    background-attachment: scroll;
			}";
            $this->cssTags[] = "<style>$bstyle</style>";
		}

		return implode(PHP_EOL, [
            implode(PHP_EOL, $this->cssTags),
            implode(PHP_EOL, $this->jsTags["top"])
        ]);
	}

    public function makeBottomScripts() {
        // Special script for index.
        $fbAlpha = $this->fbAlpha;
        if($this->isIndex) {
            if(Compatibility::check("blur_bg")) {
                // Do the blur! hur hur hur.
                // FIXME: Did I spell "blur" right? o.o
                $script =
                "window.addEventListener('scroll', function(){
                        $('#blurr-bg').css({opacity: (1-$('#intro').visibility())});
                });";
            } else {
                // Since the blur effect would cause issues, let's just darken the BG more.
                $script =
                "window.addEventListener('scroll', function(){
                    $('#outerContent').css({
                        background: 'rgba(0,0,0,'+(
                            $fbAlpha-($fbAlpha*$('#intro').visibility())
                        )+')'
                    });
                });";
            }
            $this->jsTags["bottom"][] = '<script type="text/javascript">'.$script.'</script>';
        }
        return implode(PHP_EOL, $this->jsTags["bottom"]);
    }

	public function render($name, array $args = []) {
		try {
	 		return FlipFlop::loadWithContext($name, $args, $this);
		} catch(\Exception $e) {
			return $e->getMessage();
		}
	}

	public function renderPartial($name, array $args = []) {
        $args = array_merge($args, ["__partial__"=>true]);
		$view = FlipFlop::loadWithContext($name, $args, $this);
        return $view;
	}

    public function renderPartialFromFile($name, array $args = []) {
        $args = array_merge($args, ["__partial__"=>true]);
        $view = View::file($name, $args);
        $view->getEngine()->setContext($this);
        return $view();
    }

}
