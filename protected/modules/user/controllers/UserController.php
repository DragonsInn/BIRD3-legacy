<?php class UserController extends Controller {
    public function actionLogin() {
        $user = new User("login");
        if(isset($_POST['User'])) {
            $user->attributes=$_POST['User'];
            if($user->validate()) {
                if($user->scenario == "login") {
                    if($user->login()) {
                        $this->redirect(Yii::app()->user->returnUrl);
                    } else echo "Login noped.";
                } else echo "Scenario noped";
            } /*else {
                echo "<pre>";
                print_r($user);
                echo "</pre>";
                die();
            }*/
        }
        $this->render("loginForm",array("model"=>$user));
    }

    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->user->returnUrl);
    }
}
