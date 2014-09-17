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
            array("username, password", "required"),
            array('email', 'email'),
            array('email', 'required', 'on'=>'register'),
            array(
                "username, email", "unique", 'on'=>'register',
                "allowEmpty"=>false, "attributeName"=>null
            ),
            array('password_repeat', 'required', 'on'=>'register'),
            array('password', 'compare', 'compareAttribute'=>'password_repeat', 'on'=>'register'),
            array("password", "checkValidPassword", "on"=>"login"),
        );
    }

    public function search() {
        $crit = new CDbCriteria();
        $crit->compare("id",$this->id);
        $crit->compare("username",$this->username, true);
        $crit->compare("email",$this->email, true);
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

    // Needs editing for user updates etc.
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

    // User login logic.
    private $_idendity;

    public function checkValidPassword($attr,$params) {
        if(!$this->hasErrors()) {
            $this->_idendity = new BIRD3UserIdendity($this->username, $this->password);
            if($this->_idendity->authenticate() == BIRD3UserIdendity::ERROR_NONE) {
                $this->addError("password", "Password invalid!");
            }
        }
    }

    public function login() {
        if($this->_idendity==null) {
            $this->_idendity=new BIRD3UserIdendity($this->username, $this->password);
            $this->_idendity->authenticate();
        }
        if($this->_idendity->errorCode==BIRD3UserIdendity::ERROR_NONE) {
            if($this->_idendity->id == NULL) throw new CException("WTF, no user id??");
            $rememberMe=3600*24*30;
            Yii::app()->user->login($this->_idendity, $rememberMe);
            return true;
        } else return false;
    }

}
