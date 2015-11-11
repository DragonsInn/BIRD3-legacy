<?php namespace BIRD3\App\Controllers;

use BIRD3\Foundation\BaseController;

class MainController extends BaseController {
    public function getIndex() {
        $this->isIndex = true;
        return $this->render("site/index");
    }
    public function getStat() {
        return $this->render("test");
    }
}
