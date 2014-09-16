<?php class UserController extends Controller {
    public function actionLogin() {
        $user = new User("login");
        if(isset($_POST['User'])) {
            $user->attributes=$_POST['User'];
            $at = $user->authentificate();
            if($at == BIRD3UserIdendity::ERROR_NONE) {
                $user->login();
            }
        }
        $this->render("loginForm",array("model"=>$user));
    }
}
