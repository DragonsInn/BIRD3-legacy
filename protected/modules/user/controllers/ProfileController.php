<?php class ProfileController extends Controller {
    public function actionView($name) {
        $this->rqMarkdown = true;
        if(is_numeric($name)) {
            $user = User::model()->findByPk($name);
        } else {
            $user = User::model()->findByAttributes(["username"=>$name]);
        }
        if(!$user) {
            throw new CException("User ({$name}) was not found.");
        } else {
            $this->pageTitle = "User Profile: ".$user->username;
            $this->render("page", ["user"=>$user, "profile"=>$user->profile]);
        }
    }
}
