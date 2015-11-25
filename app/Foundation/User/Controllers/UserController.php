<?php namespace BIRD3\Foundation\User\Controllers;

use BIRD3\Foundation\BaseController;
use BIRD3\Foundation\User\Entity as User;

use Request;
use Auth;

class UserController extends BaseController {

    protected $redirectPath = "/";

    public function getLogin() {
        return $this->render("User::login");
    }

    public function postLogin() {
        $creds = [
            "username" => Request::input("username"),
            "password" => Request::input("password")
        ];
        if(Auth::attempt($creds)) {
            return redirect()->intended($this->redirectPath);
        } else {
            return response("Could not log in. ".var_export($creds,true));
        }
    }

    public function getLogout() {
        $this->middleware("auth");
        Auth::logout();
        return redirect("/");
    }

    // Do the registration and validation
    // Generate activation key and email it.
    public function postRegister() {
        $user->attributes=$_POST["User"];
        $user->activkey = md5($user->email)."-".uniqid();
        if(!$user->save()) {
            $this->render("register",["model"=>$user]);
        } else {
            $this->render("register_success",["model"=>$user]);
        }
    }
    public function getRegister() {
        if(Auth::check()) return redirect("/");
        return $this->render("User::register");
    }

    // Check the activation key and activate the user.
    public function getActivate($key) {
        $this->pageTitle = "Account activation";
        // Look for $key in the DB. If exists, show success and set user to STATUS_ACTIVE.
        // Else, error. o.o
        // FIXME: Do it, actually.
        $this->render("activate");
    }

    // let the user change some settings
    public function getSettings() {
        $this->middleware("auth");
        $this->pageTitle = "Settings";
        $model = Auth::user();
        return $this->render("User::settings", ["model"=>$model]);
    }
    public function postSettings() {
        $this->middleware("auth");
        $this->pageTitle = "Settings";
        $user = Auth::user();
        if(
            Request::has("User")
            && Request::has("UserSettings")
            && Request::has("UserProfile")
        ) {
            $user->fill(Request::input("User"));
            $user->profile->fill(Request::input("UserProfile"));
            $user->settings->fill(Request::input("UserSettings"));

            if(
                $user->update()
                && $profile->update()
                && $settings->update()
            ) {
                return redirect("/user/profile");
            }
        }
        return $this->render("User::settings",["model"=>$user]);
    }

    // Because it happens.
    public function getForgotPassword() {
        if(Auth::check()) return redirect("/");
        $this->pageTitle = "Forgot password";
        $this->render("forgot_password");
    }

    // FIXME: Eloquent has pagination o.o
    public function getList($page=1,$q=false) {
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

    public function getChangeAvatar() {
        $this->middleware("auth");
        $this->pageTitle = "Change profile picture";
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
                        $p_path = "$avviePath/{$me->id}.{$old_ext}";
                        if(!empty($old_ext) && file_exists($p_path)) {
                            @unlink($p_path);
                            $me->profile->avvie_ext = null;
                            $me->profile->update();
                        } else if(!empty($old_ext) && !file_exists($p_path)) {
                            // There is a previous derp here. Huh.
                            $me->profile->avvie_ext = null;
                            $me->profile->update();
                        }

                        // Last check - size.
                        $isize = getimagesize($timg);
                        $newext = "png";
                        list($iw, $ih) = $isize; // Obtain 0 and 1
                        if($iw > 150 && $ih > 150) {
                            /*
                            Log::info("Step 5.1");
                            // Image is NOT resized... Ugh, so we have to actually do it.
                            // Only specific formats are supported.
                            // We resize the image and save it down.
                            $ea = new EasyImage($timg);
                            $ea->resize(150, 150, EasyImage::RESIZE_AUTO);
                            Log::info("Step 5.2");
                            $ea->render($newext);
                            Log::info("Step 5.3");
                            $ea->save("$avviePath/{$me->id}.$newext");
                            Log::info("Step 5.4");
                            $me->profile->avvie_ext = $newext;
                            */
                            // There is currently an error that segfaults PHP.
                            // FIXME: Resize implementation - PLEASE.
                            $res["error"]=true;
                            $res["code"]=-1;
                        } else {
                            // It already is resized. Obtain neccessary infos...
                            $new_ext = Mimex::extension($timg);
                            $me->profile->avvie_ext = $new_ext;
                            $path = "$avviePath/{$me->id}.{$new_ext}"; # String templating rocks.
                            if(!rename($timg, $path)) {
                                $res["error"]=true;
                                $res["code"]=-4;
                            }
                        }
                        // Update... FIXME: Optimize me.
                        if(isset($res["error"])) {
                            if($res["error"] != false) {
                                $me->profile->update();
                            }
                        } else $me->profile->update();
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

    public function getProfile($id=null) {
        $user = null;
        if(is_null($id)) {
            if(Auth::check()) {
                $user = Auth::user();
            } else {
                return redirect("/user/login");
            }
        } else if(is_numeric($id)) {
            $user = User::with("profile")->findOrFail($id);
        } else {
            $user = User::with("profile")->where("username", $id)->findOrFail();
        }
        return $this->render("User::profile",[
            "user"=>$user,
            "profile"=>$user->profile
        ]);
    }
}
