<?php class UserController extends Controller {
    public function actionLogin() {
        $user = new User("login");
        if(isset($_POST['User'])) {
            $user->attributes=$_POST['User'];
            if($user->validate()) {
                if($user->login()) {
                    $this->redirect(Yii::app()->user->returnUrl);
                } else echo "Login noped.";
            }
        }
        $this->render("loginForm",array("model"=>$user));
    }

    public function actionLogout() {
        // We don't need the user's data neither on the client or server.
        Yii::app()->request->cookies->clear();
        Yii::app()->session->clear();
        Yii::app()->session->destroy();
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->user->returnUrl);
    }

    // Do the registration and validation
    // Generate activation key and email it.
    public function actionRegister() {
        $user = new User("register");
        if(!isset($_POST["User"])) {
            $this->render("register",["model"=>$user]);
        } else {
            $user->attributes=$_POST["User"];
            if(!$user->save()) {
                $this->render("register",["model"=>$user]);
            } else {
                $this->render("register_success",["model"=>$user]);
            }
        }
    }

    // Check the activation key and activate the user.
    public function actionActivate($key) {
        $this->render("activate");
    }

    // let the user change some settings
    public function actionSettings() {
        $this->render("settings");
    }

    // Because it happens.
    public function actionForgot_password() {
        $this->render("forgot_password");
    }
}
