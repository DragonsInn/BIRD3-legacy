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
        $avvie = Yii::app()->cdn->avvie($id);
        $finfo = new finfo(FILEINFO_MIME);
        $mt = $finfo->file($avvie);
        header("Content-type: $mt");
        header("Content-length: ".filesize($avvie));
        Yii::app()->clientCache->makeHeaders($avvie, ClientCache::FILE);
        readfile($avvie);
        Yii::app()->end();
    }
}
