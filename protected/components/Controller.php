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

	// Used to load the various scripts in
	public $rqSwitch=false;
	public $rqMarkdown=true;
	public $rqUpload=false;
	public $rqColorPicker=false;
	public $rqCaret=false;

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
		"Chat <font color=\"lime\">NN</font>"=>array(
			"href"=>array("/chat"),
			"icon"=>"fa fa-comments"
		),
		"Hotel"=>array(
			"href"=>"#",
			"icon"=>"fa fa-globe",
			"entries"=>array(
				array("Story", "icon"=>"fa fa-file-text","url"=>array("/hotel/story")),
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
		"Community"=>array(
			"href"=>"#",
			"icon"=>"fa fa-users",
			"entries"=>array(
				array("Users", "icon"=>"fa fa-users","url"=>array("/user/list")),
				array("Forum", "icon"=>"fa fa-comment","url"=>array("/form")),
				array("Blogs", "icon"=>"glyphicon glyphicon-list-alt","url"=>array("/blog"))
			)
		),
	);

	public function init() {
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

		$tbase = Yii::app()->theme->baseUrl;
		$cdnUrl = Yii::app()->cdn->baseUrl;
		$yiiUrl = Yii::app()->request->getBaseUrl(true);
		$escYiiUrl = str_replace('/','\/', $yiiUrl);

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
		$cdnApp = Yii::app()->cdn->baseUrl."/app";
		// These settings are mandatory.
		$cs->registerScript("settings","window.BIRD3 = {
			baseUrl: '$escYiiUrl'
		}", CClientScript::POS_HEAD);
		// Load the "library" first.
		$cs->registerScriptFile(
			"$cdnApp/$hash-libwebpack.js",
			CClientScript::POS_END
		);
		// Now load the stuff itself.
		#$cs->registerScriptFile("$cdnApp/$hash-compatibility.js", CClientScript::POS_END);
		$cs->registerScriptFile(
			"$cdnApp/$hash-main.js",
			CClientScript::POS_END
		);
		// The design.
		$cs->registerCssFile("$cdnApp/$hash-main.css");

		// Special script for index.
		$fbAlpha = 0.3;
		if($this->isIndex && Yii::app()->user->isGuest) {
			if(Compatibility::check("blur_bg")) {
				// Do the blur! hur hur hur.
				// FIXME: Did I spell "blur" right? o.o
				$cs->registerScript("fracs+intro",
					"$.ready(function(){
						window.addEventListener('scroll', function(){
							$('#blurr-bg').css({opacity: (1-$('#intro').visibility())});
						});
					});",
				CClientScript::POS_END);
			} else {
				// Since the blur effect would cause issues, let's just darken the BG more.
				$cs->registerScript("fracs+intro",
					"$.ready(function(){
						window.addEventListener('scroll', function(){
							$('#outerContent').css({
								background: 'rgba(0,0,0,'+(
									$fbAlpha-($fbAlpha*$('#intro').visibility())
								)+')'
							});
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


		// Aditions
		if($this->rqSwitch) $this->requireSwitch();
		if($this->rqMarkdown) $this->requireMarkdown();
		if($this->rqUpload) $this->requireUpload();
		if($this->rqColorPicker) $this->requireColorPicker();
		if($this->rqCaret) $this->requireCaret();
	}

	public function requireColorPicker() {
		$cdn = Yii::app()->cdn
			->alt("js", "pick-a-color/js/tinycolor-0.9.15.min.js")
			->alt("js", "pick-a-color/js/pick-a-color-1.2.3.min.js")
			->alt("css", "pick-a-color/css/pick-a-color-1.2.3.min.css");
	}
	public function requireSwitch() {
		Yii::app()->cdn
			->js("bootstrap-ddselect.js")
			->js("bootstrap-checkbox.js");
	}
	public function requireMarkdown() {
		# Noop
	}
	public function requireMaxlength() {
		Yii::app()->cdn
			->js("bootstrap-maxlength.js");
	}
	public function requireLiveSearch() {
		Yii::app()->cdn
			->js("typeahead.bundle.js");
	}
	public function requireUpload() {
		// JavaScript Load Image
		Yii::app()->cdn
			->alt("js", "js-load-image/js/load-image.all.min.js");

		// Canvas To Blob
		Yii::app()->cdn
			->alt("js", "canvas-to-blob/js/canvas-to-blob.min.js");

		// Internet Explorer Canvas
		Yii::app()->cdn
			->alt("js", "excanvas/excanvas.js");

		// Blueimp/jQuery File Upload
		$jqs="jquery-file-upload/js";
		Yii::app()->cdn
			->alt("js", "$jqs/vendor/jquery.ui.widget.js")
			->alt("js", "$jqs/jquery.iframe-transport.js")
			->alt("js", "$jqs/jquery.fileupload.js")
			->alt("js", "$jqs/jquery.fileupload-process.js")
			->alt("js", "$jqs/jquery.fileupload-image.js")
			->alt("js", "$jqs/jquery.fileupload-video.js")
			->alt("js", "$jqs/jquery.fileupload-audio.js");

		// jQuery Knob
		Yii::app()->cdn
			->alt("js", "jquery-knob/dist/jquery.knob.min.js");
	}
	public function requireCaret() {
		Yii::app()->cdn
			->js("rangyinputs-jquery.js")
			->js("autogrow.min.js");

		Yii::app()->clientScript->registerScript("autogrow",
			"$('textarea[data-autogrow=\"true\"]').autogrow({onInitialize: true});",
		CClientScript::POS_READY);
	}

	public function render($view, $data = null, $return = false, $options = null) {
		$output = parent::render($view, $data, true);
		$compactor = Yii::app()->contentCompactor;
		if($compactor == null) {
			throw new CHttpException(500, Yii::t('messages',
				'Missing component ContentCompactor in configuration.'
			));
		}
		$coutput = $compactor->compact($output, $options);
		if($return)
			return $coutput;
		else
			echo $coutput;
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
