<?php class BIRD3UserIdendity extends CUserIdentity {

    private $_username;
    private $_id;

    public function authenticate() {
        $model = User::model()->findByAttributes(array(
            "username" => $this->username
        ));
        if($model == NULL) {
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        } else if(Password::verify($this->password, $model->password)) {
            // BIRD3 password
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        } else if(md5($this->password) != $model->password) {
            // BIRD2 password. HELL NO UPDATE THIS.
            $newPwd = Password::hash($this->password);
            $model->password = $newPwd;
            if(!$model->update()) {
                throw new CException(implode("<br/>\n", [
                    "An error was encountered while updating your password hash.",

                    "Please use the front page's issue dialog to contact the staff."
                    ." If you can not reach it, clear any cookies related to the Inn,"
                    ." and try again.",

                    "The password update is performed to further secure your account.",
                    "Thanks for your understanding!"
                ]));
            }
            // ...and then let them go.
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
