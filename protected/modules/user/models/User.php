<?php class User extends CActiveRecord {

    const R_BANN  = -1;
    const R_USER  =  0;
    const R_VIP   =  1;
    const R_MOD   =  2;
    const R_ADMIN =  3;

    public static function model($className=__CLASS__) {
        return parent::model($className);
    }
    public function tableName() {
        return "{{users}}";
    }

    public function scopes() {
        return array(
            'banned'=>array(
                'condition'=>'superuser='.self::R_BANN
            ),
            'vips'=>array(
                'condition'=>'superuser='.self::R_VIP
            ),
            'mods'=>array(
                'condition'=>'superuser='.self::R_MOD
            ),
            'admins'=>array(
                'condition'=>'superuser='.self::R_ADMIN
            ),
        );
    }

    public function rules() {
        return array(
            array("username, password, email", "required"),
            array('email', 'email'),
            array("username, email", "unique", "allowEmpty"=>false, "attributeName"=>null),
            array('password_repeat', 'required', 'on'=>'register'),
            array('password', 'compare', 'compareAttribute'=>'password_repeat', 'on'=>'register'),
        );
    }

    public function search() {
        $crit = new CDbCriteria();
        $crit->compare("id",$this->id);
        $crit->compare("username",$this->username, true);
        return new CActiveDataProvider($this, array(
            'criteria'=>$crit,
        ));
    }

    /*public function relations() {
        return array(
            'profile'=>array(self::HAS_ONE, 'UserProfile', 'u_id'),
            'characters'=>array(self::HAS_MANY, "Character", "uID"),
            'gallery'=>array(self::HAS_ONE, "Gallery", "u_id"),
            'blogPosts'=>array(self::HAS_MANY, "BlogPost", "u_id"),
            'forumTopics'=>array(self::HAS_MANY, "ForumTopic", "u_id"),
            'forumPosts'=>array(self::HAS_MANY, "ForumPost", 'u_id')
        );
    }*/

    public function beforeSave() {
        parent::beforeSave();
        if($this->isNewRecord) {
            $this->password = md5($this->password);
        }
    }

    public function attributeLabels() {
        return array(
            'id'=>'ID',
            'username'=>'Username',
            'password'=>'Password',
            'email'=>'E-Mail',
        );
    }

    // Yeah, we have to.
    public function authentificate() {
        $id = new BIRD3UserIdendity($this->username, $this->password);
        $id->authentificate();
        switch($id->errorCode) {
            case BIRD3UserIdendity::ERROR_USERNAME_INVALID:
                $this->addError("username", "Username invalid!");
            break;
            case BIRD3UserIdendity::ERROR_PASSWORD_INVALID:
                $this->addError("password", "Password invalid!");
            break;
        }
        return $id->errorCode;
    }

    public function login() {
        $id = new BIRD3UserIdendity($this->username, $this->password);
        Yii::app()->user->login($id, 3000*24);
        Yii::app()->controller->redirect(Yii::app()->user->returnUrl);
    }

}
