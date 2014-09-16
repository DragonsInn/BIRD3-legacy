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

	public function beforeAction($action) {
    	if( parent::beforeAction($action) ) {
        	$cs = Yii::app()->clientScript;
        	$theme = Yii::app()->theme;

        	$cs->registerPackage('jquery');
        	$cs->registerScriptFile( Yii::app()->cdn->getBaseUrl() . '/js/jquery.sidr.min.js' );
			$cs->registerScriptFile( Yii::app()->cdn->getBaseUrl() . '/js/socket.io.js' );

        	return true;
    	}
    	return false;
	}
}
