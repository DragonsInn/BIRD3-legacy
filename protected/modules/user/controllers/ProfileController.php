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
        $avvie = "";
        $key = "user-avvie-$id";
        $cache = Yii::app()->cache;
        if($cache->offsetExists($key)) {
            $avvie = $cache->get($key);
        } else {
            $profile = UserProfile::model()->findByPk($id);
            $avvie = $profile->avatar;
            $cache->set($key, $avvie);
        }
        $finfo = new finfo(FILEINFO_MIME);
        $mt = $finfo->buffer($avvie);
        header("Content-type: $mt");
        header("Content-length: ".strlen($avvie));
        Yii::app()->clientCache->makeHeaders($avvie);
        echo $avvie;
        Yii::app()->end();
    }
}
