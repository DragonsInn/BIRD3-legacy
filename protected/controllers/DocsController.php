<?php class DocsController extends Controller {
    public function actionShow($name) {
        $this->render("display", ["name"=>$name]);
    }
}
