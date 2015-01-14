<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	public $layout='//layouts/main';
	public $menu=array();
	public $breadcrumbs=array();

	// Style stuff...
	public $og_type="website";
	public $og_image="";
	public $panelBottom=false;
	public $tabbar;
	public $leftSide;
	public $rightSide;

	// Set to true to get ALL the page.
	public $allPage=false;

	// Used to load the various scripts in
	public $rqSwitch=false;
	public $rqMarkdown=false;

	// Menu
	public $navEntries = array(
		"Dragon's Inn"=>array(
			"href"=>"#",
			"entries"=>array(
				array("Home", "icon"=>"fa fa-home", "url"=>array("/")),
				array("<font color=\"red\">Rules/TOS</font>", "icon"=>"fa fa-legal", "url"=>array("/docs/rules")),
				array("Staff", "icon"=>"glyphicon glyphicon-certificate", "url"=>array("/home/staff")),
				array("Infos/Credits", "icon"=>"fa fa-exclamation", "url"=>array("/home/infos")),
				array("Manage", "icon"=>"fa fa-cogs","url"=>array("/home/manage"))
			)
		),
		"Chat <font color=lime>NN</font>"=>array(
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
				array("Jobs", "icon"=>"fa fa-building","url"=>array("/chars/jobs"))
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

	public function registerScripts() {
		$cs = Yii::app()->clientScript;

		$tbase = Yii::app()->theme->baseUrl;
		$cdnUrl = Yii::app()->cdn->baseUrl;
		$yiiUrl = Yii::app()->request->getBaseUrl(true);

		// Gracefuly update jQuery :)
		$cs->scriptMap["jquery.js"]=false;

		// jQuery
		Yii::app()->cdn
			->js("jquery-1.11.1.js")
			->js("jquery-migrate-1.2.1.js")
			->js('jquery.easytabs.js');

		Yii::app()->cdn
			->alt("js", "/bootstrap/js/bootstrap.min.js")
			->alt("js", "/bootstrap-accessibility/js/bootstrap-accessibility.min.js")
			->alt("js", "/mmenu/js/jquery.mmenu.min.all.js")
			->js('socket.io.js')
			->js('delivery.js');

		Yii::app()->cdn
			->css("normalize.css")
			->alt("css", "/bootstrap/css/bootstrap-cyborg.css")
			->alt("css", "/bootstrap-accessibility/css/bootstrap-accessibility.css")
			->alt("css", "/mmenu/css/jquery.mmenu.all.css")
			->css("social-buttons.css")
			->alt("css", "/font-awesome/css/font-awesome.css");

		Yii::app()->cdn
			->css("m-buttons.css")
			->css("m-forms.css")
			->css("m-icons.css")
			->js("m-dropdown.js")
			->js("m-radio.js");

		Yii::app()->cdn
			->js("oj-runtime.js");

		// BIRD3 Theme. We use the URL here to avoid minification. Trickery, yo.
		$cs->registerCssFile($yiiUrl.$tbase."/css/main.ws.php");
		$cs->registerScriptFile($yiiUrl."/cdn/oj/BIRD3.oj");
		$cs->registerCssFile($tbase."/css/bs-extra.css");
		$cs->registerScriptFile($tbase."/js/panels.js");
		Yii::app()->cdn
			->css("bs-tabs-extended.css");

		$faBase = $cdnUrl."/font-awesome";
		$bsBase = $cdnUrl."/bootstrap";
		$mImg = $cdnUrl."/metro-img";
		$cs->registerCss("fa_bs-fix","/* Fixes begin */
		/* FontAwesome fix. Generated. */
		@font-face {
			font-family: 'FontAwesome';
			src: url('{$faBase}/fonts/fontawesome-webfont.eot?v=4.2.0');
			src: url('{$faBase}/fonts/fontawesome-webfont.eot?#iefix') format('embedded-opentype'),
			     url('{$faBase}/fonts/fontawesome-webfont.woff?v=4.2.0') format('woff'),
				 url('{$faBase}/fonts/fontawesome-webfont.ttf?v=4.2.0') format('truetype'),
				 url('{$faBase}/fonts/fontawesome-webfont.svg?v=4.2.0#fontawesomeregular') format('svg');
			font-weight: normal;
			font-style: normal;
		}
		/* Glyphcon fix */
		@font-face {
  			font-family: 'Glyphicons Halflings';
			src: url('{$bsBase}/fonts/glyphicons-halflings-regular.eot');
			src: url('{$bsBase}/fonts/glyphicons-halflings-regular.eot?#iefix') format('embedded-opentype'),
				 url('{$bsBase}/fonts/glyphicons-halflings-regular.woff') format('woff'),
				 url('{$bsBase}/fonts/glyphicons-halflings-regular.ttf') format('truetype'),
				 url('{$bsBase}/fonts/glyphicons-halflings-regular.svg#glyphicons') format('svg');
		}
		/* Metro Icon fix */
		.m-btn [class^=\"icon-\"] { background-image: url({$mImg}/glyphicons-halflings.png); }
		.m-btn .icon-white        { background-image: url({$mImg}/glyphicons-halflings-white.png); }
		[class^=\"m-icon-\"]      { background-image: url({$mImg}/syncfusion-icons.png); }
		[class^=\"m-icon-big-\"]  { background-image: url({$mImg}/syncfusion-icons.png); }
		.m-icon-white             { background-image: url({$mImg}/syncfusion-icons-white.png); }
		/* Fixes end */");

		// Aditions
		if($this->rqSwitch) $this->requireSwitch();
		if($this->rqMarkdown) $this->requireMarkdown();
	}

	public function requireColorPicker() {
		$cdn = Yii::app()->cdn
			->alt("js", "/pick-a-color/js/tinycolor-0.9.15.min.js")
			->alt("js", "/pick-a-color/js/pick-a-color-1.2.3.min.js")
			->alt("css", "/pick-a-color/css/pick-a-color-1.2.3.min.css");
	}
	public function requireSwitch() {
		Yii::app()->cdn
			->js("bootstrap-ddselect.js")
			->js("bootstrap-checkbox.js");
	}
	public function requireMarkdown() {
		Yii::app()->cdn
			->css("bootstrap-markdown.min.css")
			->js("bootstrap-markdown.js")
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
