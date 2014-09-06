<?php class BIRD3User extends CWebUser {

    private $model;

    public function login($idendity, $duration=0) {
        // Catch the model so we can access it later.
        $this->model=$idendity->model;
        parent::login($idendity, $duration);
    }
    public function logout($killSession=true) {
        $this->model=null;
        parent::logout($killSession);
    }

    public function isAdmin() {
        return ($this->model->superuser == User::R_ADMIN);
    }
    public function isMod() {
        return (
            $this->model->superuser == User::R_MOD
            || $this->isAdmin()
        );
    }
    public function isVIP() {
        return (
            $this->model->superuser == User::R_VIP
            || $this->isMod()
            || $this->isAdmin()
        );
    }
    public function isUser() {
        return (
            $this->model->superuser == User::R_USER
            || $this->isMod()
            || $this->isVIP()
            || $this->isAdmin()
        );
    }
    public function isBanned() {
        return ($this->model->superuser == User::R_BANN);
    }
    public function isGuest() {
        return is_null($this->model);
    }

    public function getUsername() { return $model->username; }
    public function getId()       { return $model->id;       }
    public function getEmail()    { return $model->email;    }
    #public function getProfile() { return $model->profile;  } For later. o.o

}
