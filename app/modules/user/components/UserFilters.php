<?php trait UserFilters {
    public function filterMust_be_logged_in($fc) {
        if(Yii::app()->user->isGuest) {
            $this->redirect("/user/login");
        }
        return $fc->run();
    }
    public function filterMust_be_logged_out($fc) {
        if(!Yii::app()->user->isGuest) {
            throw new CException("You are already logged in.");
        }
        return $fc->run();
    }
}
