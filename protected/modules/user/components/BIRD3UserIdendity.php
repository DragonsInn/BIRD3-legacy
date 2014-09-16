<?php class BIRD3UserIdendity extends CUserIdentity {

    private $_model;
    private $_id;

    public function authentificate() {
        if($this->_model == null) {
            $model = User::model()->findByAttributes(array(
                "username" => $this->username
            ));
        } else {
            $model = $this->_model;
        }
        if($model == NULL) {
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        } else if(md5($this->password) != $model->password) {
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        } else {
            $this->errorCode=self::ERROR_NONE;
            $this->_id=$model->id;
            $this->_model=$model;
        }
        return $this->errorCode;
    }

    public function getId() {
        return $this->_id;
    }

    public function getModel() {
        return $this->_model;
    }
    public function setModel($m) {
        $this->_model=$m;
    }
}
