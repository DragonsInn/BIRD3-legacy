<?php class ManageController extends Controller {

    public function actionCreate() {
        $this->rqSwitch = true;
        $this->rqMarkdown = true;
        $model = new Character;
        $this->render("form", array("model"=>$model));
    }

}
