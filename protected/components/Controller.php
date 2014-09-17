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
		$dr = Yii::app()->dynamicRes;

		$tbase = Yii::app()->theme->baseUrl;
		$cdnUrl = Yii::app()->cdn->baseUrl;

		$cs->registerPackage('jquery');
		$cs->registerScriptFile('/socket.io/socket.io.js');

		$dr->registerScriptFile($tbase."/js/panels.js");
		$dr->registerScriptFile($cdnUrl.'/js/jquery.sidr.min.js');
		$dr->registerScriptFile($cdnUrl."/bootstrap/js/bootstrap.min.js");
		$dr->registerScriptFile($cdnUrl."/bootstrap-accessibility/js/bootstrap-accessibility.min.js");

		$dr->registerCssFile($cdnUrl."/css/normalize.css");
		$dr->registerCssFile($cdnUrl."/bootstrap/css/bootstrap.min.css");
		$dr->registerCssFile($cdnUrl."/bootstrap-accessibility/css/bootstrap-accessibility.css");
		$dr->registerCssFile($cdnUrl."/font-awesome/css/font-awesome.min.css");
	}

	public function afterRender($view, &$output) {
		parent::afterRender($view, $output);
		Yii::app()->dynamicRes->saveScheme();
	}

	public function loadColorPicker() {
		#$dr->registerScriptFile($cdnUrl."/pick-a-color/js/tinycolor-0.9.15.min.js");
		#$dr->registerScriptFile($cdnUrl."/pick-a-color/js/pick-a-color-1.2.3.min.js");
		#$dr->registerCssFile($cdnUrl."/pick-a-color/css/pick-a-color-1.2.3.min.css");
	}
}
