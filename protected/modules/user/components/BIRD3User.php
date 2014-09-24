<?php class BIRD3User extends CWebUser {

    private $_model=null;

    public function loadUser() {
        if($this->_model == null){
            $this->_model = User::model()->findByPk(Yii::app()->user->id);
        }
        if($this->_model==null) {
            var_dump($this->_model);
            die();
        }
        return $this->_model;
    }

    #public function beforeLogin($id, $states, $fromCookie) {
    #}

    public function isAdmin() {
        return ($this->loadUser()->superuser == User::R_ADMIN);
    }
    public function isMod() {
        return (
            $this->loadUser()->superuser == User::R_MOD
            || $this->isAdmin()
        );
    }
    public function isVIP() {
        return (
            $this->loadUser()->superuser == User::R_VIP
            || $this->isMod()
            || $this->isAdmin()
        );
    }
    public function isUser() {
        return (
            $this->loadUser()->superuser == User::R_USER
            || $this->isMod()
            || $this->isVIP()
            || $this->isAdmin()
        );
    }
    public function isBanned() {
        return ($this->loadUser()->superuser == User::R_BANN);
    }

    public function getUsername() { return $this->loadUser()->username; }
    #public function getId()       { return $this->loadUser()->id;       }
    public function getEmail()    { return $this->loadUser()->email;    }
    #public function getProfile() { return $model->profile;  } For later. o.o

}
