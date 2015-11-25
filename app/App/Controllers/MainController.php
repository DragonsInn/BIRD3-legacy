<?php namespace BIRD3\App\Controllers;

use BIRD3\Foundation\BaseController;
use View;

class MainController extends BaseController {
    public function getIndex() {
        $this->isIndex = true;
        return $this->render("site.index");
    }
}
