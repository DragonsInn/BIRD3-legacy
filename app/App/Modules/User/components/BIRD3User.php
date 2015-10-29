<?php class BIRD3User extends CWebUser {

    private $_model=null;

    private function loadUser() {
        if($this->_model == null){
            $this->_model = User::model()->findByPk(Yii::app()->user->id);
        }
        if($this->_model==null) {
        //    var_dump($this->_model);
        //    print_r(Yii::app()->request->cookies);
        //    die();
        }
        return $this->_model;
    }

    #public function beforeLogin($id, $states, $fromCookie) {
    #}

    public function isAdmin() {
        return !$this->isGuest && $this->loadUser()->superuser == User::R_ADMIN;
    }
    public function isMod() {
        return !$this->isGuest && (
            $this->loadUser()->superuser == User::R_MOD
            || $this->isAdmin()
        );
    }
    public function isVIP() {
        return !$this->isGuest && (
            $this->loadUser()->superuser == User::R_VIP
            || $this->isMod()
            || $this->isAdmin()
        );
    }
    public function isUser() {
        return !$this->isGuest && (
            $this->loadUser()->superuser == User::R_USER
            || $this->isMod()
            || $this->isVIP()
            || $this->isAdmin()
        );
    }
    public function isBanned() {
        return !$this->isGuest && $this->loadUser()->superuser == User::R_BANN;
    }

    public function getUsername()  { return $this->loadUser()->username;  }
    public function getEmail()     { return $this->loadUser()->email;     }
    public function getProfile()   { return $model->loadUser()->profile;  }
    public function getDeveloper() { return $this->loadUser()->developer; }

    // Dangerous. But...suicide is a bad idea, just keep it in mind.
    public function getModel()     { return $this->loadUser(); }
}