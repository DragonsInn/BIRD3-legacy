<?php class UserController extends Controller {
    public function actionLogin() {
        $this->pageTitle = "User login";
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
            $user->activkey = md5($user->email)."-".uniqid();
            if(!$user->save()) {
                $this->render("register",["model"=>$user]);
            } else {
                $this->render("register_success",["model"=>$user]);
            }
        }
    }

    // Check the activation key and activate the user.
    public function actionActivate($key) {
        $this->pageTitle = "Account activation";
        $this->render("activate");
    }

    // let the user change some settings
    public function actionSettings() {
        $this->pageTitle = "Settings";
        $this->rqMarkdown = true;
        $model = User::me();
        if(
            isset($_POST["User"])
            && isset($_POST["UserSettings"])
            && isset($_POST["UserProfile"])
        ) {
            $profile = $model->profile;
            $settings = $model->settings;
            $user = $model;

            // Set all to "update"
            $user->scenario = "update";
            $profile->scenario = "update";
            $settings->scenario = "update";

            // Assign
            $user->attributes = $_POST["User"];
            $profile->attributes = $_POST["UserProfile"];
            $settings->attributes = $_POST["UserSettings"];

            if($user->validate() && $profile->validate() && $settings->validate()) {
                $user->update();
                $profile->update();
                $settings->update();
            }
        }
        $this->render("settings", ["model"=>$model]);
    }

    // Because it happens.
    public function actionForgot_password() {
        $this->pageTitle = "Forgot password";
        $this->render("forgot_password");
    }

    public function actionList($page=1,$q=false) {
        $this->pageTitle = "Show all users";
        $perPage = 50;
        $limitBegin = $perPage*($page-1);
        // Determine if we should respond to an AJAX call.
        if($q == false || $q == null) {
            // Calculate the numbers
            $count = User::model()->count();
            $pages = ceil($count/$perPage);
            $remainder = $count % $perPage;
            $dbc = new CDbCriteria();
            $dbc->limit = $perPage;
            $dbc->offset = $limitBegin;
            $users = User::model()->findAll($dbc);
            if($pages < $page-1) {
                // Reset to page 1
                return $this->redirect(["/user/list"]);
            }
        } else {
            // We are queried to search for a user.
            $adp = new CActiveDataProvider("User", [
                "criteria"=>[
                    "condition"=>"username LIKE :q OR email LIKE :q",
                    "params"=>[":q"=>$q],
                    "limit"=>$perPage,
                    "offset"=>$limitBegin
                ]
            ]);
            $users = $adp->getData();
            $pages = ceil(count($users)/$perPage);
            if($pages < $page-1) {
                return $this->redirect(["/user/list", "q"=>$q]);
            }
        }
        $this->render("list", [
            "users"=>$users,
            "pages"=>$pages,
            "page"=>$page
        ]);
    }

    public function actionChangeAvatar() {
        $this->pageTitle = "Change profile picture";
        $this->rqUpload=true;
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            header("Content-type: application/json");
            #header("Connection: close");
            echo json_encode(["_POST"=>$_POST, "_FILES"=>$_FILES, "stdin"=>file_get_contents('php://input')]);
        } else $this->render("change_avatar");
    }
}
