<?php namespace BIRD3\App\Controller;

use BIRD3\Foundation\BaseController as Controller;

class SiteController extends Controller {

	public function actionIndex() {
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}

	public function actionError($e=null) {
		if($e!=null) {
			$error = $e;
		} else {
			$error = Yii::app()->errorHandler->error;
		}
		if(Yii::app()->request->isAjaxRequest) {
			echo $error['message'];
		} else {
			$this->render('error', $error);
		}
		Yii::app()->end();
	}
	public function actionFail() {
		throw new CException("Failing on purpose.");
	}
	public function actionDerp() {
		$this->render("derp");
	}
}
