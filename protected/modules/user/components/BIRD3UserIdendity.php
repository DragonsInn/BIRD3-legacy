<?php class BIRD3UserIdendity extends CUserIdentity {

    private $model;
    private $_id;

    public function authenticate() {
        $model = User::model()->findByAttributes(array(
            "userName" => $this->username
        ));
        if($model == NULL) {
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        } else if(md5($this->password) != $model->password) {
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        } else {
            $this->errorCode=self::ERROR_NONE;
            $this->_id=$model->id;
            $this->model=$model;
        }
    }

    public function getId() {
        return $this->_id;
    }

    public function getModel() {
        return $this->model;
    }
}
