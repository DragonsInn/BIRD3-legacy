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
	# User extension
	public $displayLogin=true;

	public function registerScripts() {
		$cs = Yii::app()->clientScript;

		$tbase = Yii::app()->theme->baseUrl;
		$cdnUrl = Yii::app()->cdn->baseUrl;
		$yiiUrl = Yii::app()->request->getBaseUrl(true);

		// Gracefuly update jQuery :)
		$cs->scriptMap["jquery.js"]=false;

		$cs->registerScriptFile($cdnUrl."/js/jquery-1.11.1.min.js");
		$cs->registerScriptFile($cdnUrl."/js/jquery-migrate-1.2.1.js");
		$cs->registerScriptFile($cdnUrl."/bootstrap/js/bootstrap.min.js");
		$cs->registerScriptFile($cdnUrl."/bootstrap-accessibility/js/bootstrap-accessibility.min.js");
		$cs->registerScriptFile($cdnUrl.'/js/jquery.easytabs.js');
		$cs->registerScriptFile($cdnUrl.'/js/socket.io.js');

		$cs->registerCssFile($cdnUrl."/css/normalize.css");
		$cs->registerCssFile($cdnUrl."/bootstrap/css/bootstrap.min.css");
		$cs->registerCssFile($cdnUrl."/bootstrap-accessibility/css/bootstrap-accessibility.css");
		$cs->registerCssFile($cdnUrl."/css/social-buttons.css");
		$cs->registerCssFile($cdnUrl."/font-awesome/css/font-awesome.min.css");

		$cs->registerScriptFile($cdnUrl."/pick-a-color/js/tinycolor-0.9.15.min.js");
		$cs->registerScriptFile($cdnUrl."/pick-a-color/js/pick-a-color-1.2.3.min.js");
		$cs->registerCssFile($cdnUrl."/pick-a-color/css/pick-a-color-1.2.3.min.css");

		// BIRD3 Theme. We use the URL here to avoid minification. Trickery, yo.
		$cs->registerCssFile($yiiUrl.$tbase."/css/main.ws.php");
		$cs->registerScriptFile($tbase."/js/panels.js");

		$faBase = $cdnUrl."/font-awesome";
		$cs->registerCss("fa-fix","/* FontAwesome fix. Generated. */
		@font-face {
			font-family: 'FontAwesome';
			src: url('{$faBase}/fonts/fontawesome-webfont.eot?v=4.2.0');
			src: url('{$faBase}/fonts/fontawesome-webfont.eot?#iefix&v=4.2.0') format('embedded-opentype'),
			     url('{$faBase}/fonts/fontawesome-webfont.woff?v=4.2.0') format('woff'),
				url('{$faBase}/fonts/fontawesome-webfont.ttf?v=4.2.0') format('truetype'),
				url('{$faBase}/fonts/fontawesome-webfont.svg?v=4.2.0#fontawesomeregular') format('svg');
			font-weight: normal;
			font-style: normal;
		}");
	}

	public function afterRender($view, &$output) {
		parent::afterRender($view, $output);
		#Yii::app()->dynamicRes->saveScheme();
	}

	public function loadColorPicker() {
		$cs = Yii::app()->clientScript;
		$cs->registerScriptFile($cdnUrl."/pick-a-color/js/tinycolor-0.9.15.min.js");
		$cs->registerScriptFile($cdnUrl."/pick-a-color/js/pick-a-color-1.2.3.min.js");
		$cs->registerCssFile($cdnUrl."/pick-a-color/css/pick-a-color-1.2.3.min.css");
	}
}
