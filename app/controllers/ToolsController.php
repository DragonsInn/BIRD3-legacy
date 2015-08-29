<?php class ToolsController extends Controller {
    public function actionRender_markdown() {
        if(isset($_POST["md"])) {
            echo Markdown::parse($_POST["md"]);
        }
    }
}
