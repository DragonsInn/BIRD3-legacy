<?php class ProfileController extends Controller {
    public function actionView($id) {
        $user = User::model()->findByPk($id);
        if(!$user) {
            throw new CException("User ({$id}) was not found.");
        } else {
            $this->pageTitle = "User Profile: ".$user->username;
            $this->render("page", ["user"=>$user, "profile"=>$user->profile]);
        }
    }

    public function actionAvvie($id) {
        $profile = UserProfile::model()->findByPk($id);
        $finfo = new finfo(FILEINFO_MIME);
        $mt = $finfo->buffer($profile->avatar);
        header("Content-type: $mt");
        header("Content-length: ".strlen($profile->avatar));
        echo $profile->avatar;
        Yii::app()->end();
    }
}
