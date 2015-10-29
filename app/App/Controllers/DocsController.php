<?php namespace BIRD3\App\Controller;

use BIRD3\Foundation\BaseController;

class DocsController extends BaseController {
    public function actionShow($name) {
        $this->render("display", ["name"=>$name]);
    }
}
