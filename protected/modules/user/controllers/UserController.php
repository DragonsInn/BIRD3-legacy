<?php class UserController extends Controller {

    // Access control.
    use UserFilters;
    public function filters() {
        return [
            "must_be_logged_in + logout, settings, changeAvatar",
            "must_be_logged_out + register, forgot_password, activate, login"
        ];
    }


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
        // Look for $key in the DB. If exists, show success and set user to STATUS_ACTIVE.
        // Else, error. o.o
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
        $avvieUrl = "/content/avatars";
        $avviePath = Yii::app()->cdn->getBasePath().$avvieUrl;
        $me = User::me();
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            // Testing and probing the file, creating the response.
            $res = ["code"=>0];
            if(isset($_FILES["image"])) {
                if($_FILES["image"]["error"] == 0) {
                    # It's save to assume that we can use this now.
                    $timg = $_FILES["image"]["tmp_name"];
                    $ct = Mimex::mimetype($timg);
                    if(!fnmatch("image/*", $ct)) {
                        $res["error"]=true;
                        $res["code"]=-3;
                    } else {
                        $old_ext = $me->profile->avvie_ext;
                        $new_ext = "";

                        // Check if we already have an image? Indicated, if avvie_ext is set.
                        if(!empty($old_ext)) {
                            unlink("$avviePath/{$me->id}.{$old_ext}");
                            $me->profile->avvie_ext = null;
                        }

                        // Last check - size.
                        $isize = getimagesize($timg);
                        list($iw, $ih) = $isize; // Obtain 0 and 1
                        if($iw > 150 && $ih > 150) {
                            // Image is NOT resized... Ugh, so we have to actually do it.
                            // Only specific formats are supported.
                            // We resize the image and save it down.
                            $ea = new EasyImage($timg);
                            $ea->resize(150, 150, EasyImage::RESIZE_AUTO);
                            $ea->render("png");
                            $ea->save("$avviePath/{$me->id}.png");
                            $me->profile->avvie_ext = "png";
                        } else {
                            // It already is resized. Obtain neccessary infos...
                            $new_ext = Mimex::extension($timg);
                            $me->profile->avvie_ext = $new_ext;
                            $path = "$avviePath/{$me->id}.{$new_ext}"; # String templating rocks.
                            if(!move_uploaded_file($timg, $path)) {
                                $res["error"]=true;
                                $res["code"]=-4;
                            }
                        }
                        // Update...
                        $me->profile->update();
                        // We want to show the user the new picture!
                        $res["url"]=Yii::app()->cdn->baseUrl.$avvieUrl."/{$me->id}.{$new_ext}";
                    }
                } else {
                    $res["error"]=true;
                    $res["code"]=$_FILES["image"]["error"];
                }
            }

            // Send response
            header("Content-type: application/json");
            #header("Connection: close"); ?
            echo json_encode($res);
            Yii::app()->end();
        } else $this->render("change_avatar");
    }
}
