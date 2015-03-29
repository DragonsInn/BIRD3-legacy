<?php class Controller extends CController {
	public $layout='//layouts/main';
	public $breadcrumbs=array();
	public $showingBan=false;

	// Style stuff...
	public $og_type="website";
	public $og_image="";
	public $panelBottom=false;
	public $tabbar;
	public $leftSide;
	public $rightSide;

	// Set to true to get ALL the page. At least anything between menubar and bottom.
	// Disables banner and footer.
	public $allPage=false;

	// This shows the introduction section.
	public $isIndex=false;

	// Used to load the various scripts in
	public $rqSwitch=false;
	public $rqMarkdown=false;
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

		// Gracefuly update jQuery :)
		$cs->scriptMap["jquery.js"]=false;

		// jQuery
		Yii::app()->cdn->js("jquery-1.11.1.js");

		# Bootstrap
		$vers = "3.3.4";
		Yii::app()->cdn
			->alt("css", "/bootstrap/css/bootstrap-cyborg-{$vers}.min.css")
			->alt("js", "/bootstrap/js/bootstrap.min.js")
			->alt("js", "/bootstrap-accessibility/js/bootstrap-accessibility.js");

		# Socket.IO
		Yii::app()->cdn
			->js('delivery.js')
			->js('socket.io.js');

			# Plugins
			#->js("circle-progress.js");
			#->alt("js", "/mmenu/js/jquery.mmenu.min.all.js")

		// Font Awesome
		Yii::app()->cdn
			->alt("css", "font-awesome/css/font-awesome.css");

		// Metro UI Kit
		Yii::app()->cdn
			->css("m-buttons.css")
			->css("m-forms.css")
			->css("m-icons.css")
			->js("m-dropdown.js")
			->js("m-radio.js");

		// Bootstrap dialog
		Yii::app()->cdn
			->css("bootstrap-dialog.css")
			->js("bootstrap-dialog.min.js");

		// OJ runtime
		Yii::app()->cdn->js("oj-runtime.js");

		#Yii::app()->booster->getBooster()->init();

		// BIRD3 Theme. We use the URL here to avoid minification. Trickery, yo.
		$cs->registerCssFile($yiiUrl.$tbase."/css/main.ws.php");
		$cs->registerScriptFile($yiiUrl."/cdn/oj/BIRD3.oj");
		$cs->registerCssFile($tbase."/css/bs-extra.css");
		$cs->registerScriptFile($tbase."/js/panels.js");
		#Yii::app()->cdn->css("bs-tabs-extended.css");

		$faBase = $cdnUrl."/font-awesome";
		$bsBase = $cdnUrl."/bootstrap";
		$mImg = $cdnUrl."/metro-img";
		$cs->registerCss("fa_bs-fix","
		@font-face {
			font-family: 'FontAwesome';
			src: url('{$faBase}/fonts/fontawesome-webfont.eot');
			src: url('{$faBase}/fonts/fontawesome-webfont.eot?#iefix') format('embedded-opentype'),
			     url('{$faBase}/fonts/fontawesome-webfont.woff') format('woff'),
				 url('{$faBase}/fonts/fontawesome-webfont.ttf') format('truetype'),
				 url('{$faBase}/fonts/fontawesome-webfont.svg#fontawesomeregular') format('svg');
			font-weight: normal;
			font-style: normal;
		}
		@font-face {
  			font-family: 'Glyphicons Halflings';
			src: url('{$bsBase}/fonts/glyphicons-halflings-regular.eot');
			src: url('{$bsBase}/fonts/glyphicons-halflings-regular.eot?#iefix') format('embedded-opentype'),
				 url('{$bsBase}/fonts/glyphicons-halflings-regular.woff') format('woff'),
				 url('{$bsBase}/fonts/glyphicons-halflings-regular.ttf') format('truetype'),
				 url('{$bsBase}/fonts/glyphicons-halflings-regular.svg#glyphicons') format('svg');
		}
		.m-btn [class^=\"icon-\"] { background-image: url({$mImg}/glyphicons-halflings.png); }
		.m-btn .icon-white        { background-image: url({$mImg}/glyphicons-halflings-white.png); }
		[class^=\"m-icon-\"]      { background-image: url({$mImg}/syncfusion-icons.png); }
		[class^=\"m-icon-big-\"]  { background-image: url({$mImg}/syncfusion-icons.png); }
		.m-icon-white             { background-image: url({$mImg}/syncfusion-icons-white.png); }
		");

		// Make our footer sticky
		Yii::app()->cdn
			->js("jquery.stickyfooter.min.js")
			->css("jquery.stickyfooter.css");
		$cs->registerScript("stickyFooter",
			'$("#footer").stickyFooter({content:"#MainPage"});',
		CClientScript::POS_READY);
		$cs->registerScript("bootstrap-tooltip",
			"$('[data-toggle=\"tooltip\"]').tooltip();",
		CClientScript::POS_READY);
		$cs->registerScript("bootstrap-popover",
			"$('[data-toggle=\"popover\"]').popover();",
		CClientScript::POS_READY);

		// Special script for index
		if($this->isIndex) {
			Yii::app()->cdn
				->js("jquery.fracs.min.js");
			$cs->registerScript("fracs+intro",
				"$('#intro').fracs(function(fracs, prevFracs){
					$('#blurr-bg').css({opacity: (1-fracs.visible)});
				});
				$('#app').scroll(function(){
					$('#intro').fracs('check');
				});",
			CClientScript::POS_READY);
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
		Yii::app()->cdn
			->css("jshl/hybrid.css")
			->js("highlight.pack.js");
		Yii::app()->clientScript->registerScript("jshl_init",'// JSHL init
			hljs.configure({
				tabReplace: "    "
			});
			hljs.initHighlighting();
		// end', CClientScript::POS_READY);
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
}
