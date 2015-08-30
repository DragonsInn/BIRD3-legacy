<?php class Controller extends CController {
	public $layout='//layouts/main';
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
				array("Roleplaying etiquette", "icon"=>"fa fa-info-circle", "url"=>array("/docs/Roleplaying_Etiquette")),
				array("Staff", "icon"=>"glyphicon glyphicon-certificate", "url"=>array("/home/staff")),
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
				array("Families &amp; Clans", "icon"=>"fa fa-child","url"=>array("/chars/associations")),
			),
		),
		"Media"=>array(
			"href"=>"#",
			"icon"=>"glyphicon glyphicon-eye-open",
			"entries"=>array(
				array("Latest", "icon"=>"fa fa-list","url"=>array("/media/all/latest")),
				array("All", "icon"=>"fa fa-folder","url"=>array("/media/all/list")),
				array("Art", "icon"=>"fa fa-paint-brush","url"=>array("/media/art")),
				array("Music", "icon"=>"glyphicon glyphicon-headphones","url"=>array("/media/audio")),
				array("Essay", "icon"=>"glyphicon glyphicon-bookmark","url"=>array("/media/story"))
			)
		),
	);

	public function init() {
		global $res;
		$res->header("Pragma: no-cache");
		$res->header("Cache-Control: max-age=0");
		// Amma CHEEEETAH. a CHEEEEETAH- *cough.* >v>
		include_once "Helpers.php";
		$this->og_image = Yii::app()->cdn->baseUrl."/theme/images/sign.png";
		parent::init();
		if(isset($_GET['ajax']) && $_GET['via']=="bird3") {
			// There is an action that we, and no other service, wants.
			$msg=["status"=>"ok","error"=>"none"];
			switch($_GET['action']) {
				case "user:update_visit":
					if(!Yii::app()->user->isGuest) {
						// Long statement made easy thanks to Duder.
						User::me()->lastvisit_at = time();
						if(!User::me()->update()) {
							$msg["status"] = "failed";
							$msg["error"] = "Failed to update DB record.";
						}
					}
				break;
			}
			echo json_encode($msg);
			Yii::app()->end();
		}
	}

	public function registerScripts() {
		if(Yii::app()->user->isVIP()) {
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
		}

		$cs = Yii::app()->clientScript;

		$cdnUrl = Yii::app()->cdn->baseUrl;
		$yiiUrl = Yii::app()->request->getBaseUrl(true);
		$escYiiUrl = json_encode($yiiUrl);

		// Gracefuly disable jQuery :)
		$cs->scriptMap["jquery.js"]=false;
		// Just in case.
		$cs->packages["jquery"] = [
			"basePath"=>null,
			"baseUrl"=>null,
			"js"=>null
		];

		// Load webpack stuff
		global $opt;
		$hash = $opt["userData"]["webpackHash"];
		$bower = Yii::getPathOfAlias("bower");
		$cdnApp = Yii::app()->cdn->baseUrl."/app";
		$escCdnApp = json_encode($cdnApp);
		$use = json_encode($this->panelBottom);

		// Load the loader
		$ljs = ""; $key = "include.js";
		$cache = Yii::app()->cache;
		if($cache->offsetExists($key)) {
			$ljs = $cache->get($key);
		} else {
			$ljs = file_get_contents("$bower/scriptinclude/include.min.js");
			$cache->set($key, $ljs);
		}
		$cs->registerScript("js.run","/* js runner */
			BIRD3 = window.BIRD3 = {
				baseUrl: $escYiiUrl,
				cdnUrl: $escCdnApp,
				webpackHash: '$hash',
				module: '{$this->module}',
				hash: function(f){return BIRD3.cdnUrl+'/'+BIRD3.webpackHash+'-'+f;},
				useBottomPanel: $use,
			};
			// Holy shit this is SO SO SO SO hacky. But it works XD
			BIRD3.include = (function(){ var $ljs; return include; })();
			// Modularize
			BIRD3.modules = {
				main: BIRD3.hash('main.js'),
				chat: BIRD3.hash('chat.js'),
				compatibility: BIRD3.hash('compatibility.js'),
				upload: BIRD3.hash('upload.js')
			};
			BIRD3.load = function(m){
				console.log('Loading',m);
				BIRD3.include.call(BIRD3.include, BIRD3.modules[m], function(){
					//console.log('Done',m);
				});
			};
			// Load the library
			BIRD3.include(BIRD3.hash('libwebpack.js'), function(){
				console.log('BIRD3 runtime initialized');
				BIRD3.load(BIRD3.module);
			});
		/* js runner end */", CClientScript::POS_HEAD);

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

	public function redirect($url,$terminate=true,$statusCode=302) {
		if(is_array($url)) {
			$route=isset($url[0]) ? $url[0] : '';
			$url=$this->createUrl($route,array_splice($url,1));
		}
		#Log::info("-- Redirect: $url");
		$this->real_redirect($url, $terminate, $statusCode);
	}

	public function real_redirect($url,$terminate=true,$statusCode=302) {
		if(strpos($url,'/')===0 && strpos($url,'//')!==0) {
			$url=Yii::app()->request->getHostInfo().$url;
		}
		#Log::info("-- Real redirect: $url");
		HttpResponse::header('Location: '.$url, true, $statusCode);
	}
}
