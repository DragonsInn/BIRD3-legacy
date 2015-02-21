<?php class BIRD3UserIdendity extends CUserIdentity {

    private $_username;
    private $_id;

    public function authenticate() {
        $model = User::model()->findByAttributes(array(
            "username" => $this->username
        ));
        if($model == NULL) {
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        } else if(md5($this->password) != $model->password) {
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        } else {
            $this->errorCode=self::ERROR_NONE;
            $this->_id=$model->id;
            $this->_username=$model->username;
        }
        // NO idea why its negated...?
        // 21th februrary of 2015. And I still have no answer to this. Use ::$errorCode.
        return !$this->errorCode;
    }

    public function getId()   { return $this->_id;       }
    public function getName() { return $this->_username; }

}
