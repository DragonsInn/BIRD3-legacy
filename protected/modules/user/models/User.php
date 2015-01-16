<?php class User extends CActiveRecord {

    /**
     * Database Structure
     * @int id PK               | ID
     * @string username         | Username
     * @md5 password            | Hashed password
     * @string email            | User's registration email
     * @string activkey         | Used to verify email address
     * @int superuser           | Determines between usergroups.
     * @int status              | Inactive, Active, Banned
     * @bool developer          | If user is a dev or not.
     * @timestamp create_at     | Registration time
     * @timestamp lastvisit_at  | Last visited
     */

    const R_USER     =  0;
    const R_VIP      =  1;
    const R_MOD      =  2;
    const R_ADMIN    =  3;

    const S_INACTIVE =  0;
    const S_ACTIVE   =  1;
    const S_BANNED   =  2;

    public static function model($className=__CLASS__) {
        return parent::model($className);
    }
    public function tableName() {
        return "{{users}}";
    }
    public function primaryKey() { return "id"; }

    public function scopes() {
        return array(
            'banned'=>array(
                'condition'=>'status='.self::S_BANNED
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
            'inactive'=>array(
                'condition'=>'status='.self::S_ACTIVE
            ),
            'active'=>array(
                'condition'=>'status='.self::S_INACTIVE
            )
        );
    }

    public function rules() {
        return array(
            # Register
            array('username, email, password', 'required', 'on'=>'register'),
            /*
            array('username',
                'ext.yii-antispam.CleanTalkValidator',
                'check'=>'user', // Check type message or user
                'emailAttribute'=>'email',
                'nickNameAttribute'=>'username',
                'on'=>'register'
            ),
            */
            array(
                "username, email", "unique", 'on'=>'register',
                "allowEmpty"=>false, "attributeName"=>null
            ),
            # Login
            array("username, password", "required", "on"=>"login"),
            array("password", "checkValidPassword", "on"=>"login"),
            # Search
            array("username, email", "required", "on"=>"search"),
            # Update
            array("username, password, email", "safe", "on"=>"update"),
            # Always
            array('email', 'email'),
        );
    }
    /*
        Dont forget! To use cleantalk:
        <?php echo Yii::app()->cleanTalk->checkJsHiddenField(); ?>
    */

    public function search() {
        $crit = new CDbCriteria();
        $crit->compare("id",$this->id);
        $crit->compare("username",$this->username, true);
        $crit->compare("email",$this->email, true);
        return new CActiveDataProvider($this, array(
            'criteria'=>$crit,
        ));
    }

    public function relations() {
        return array(
            // Module local
            'profile'=>array(self::HAS_ONE, 'UserProfile', 'uID'),
            'updates'=>array(self::HAS_MANY, "UserUpdate", "tID"),
            'permissions'=>array(self::HAS_MANY, "UserPermissions", "uID"),
            'settings'=>array(self::HAS_ONE, "UserSettings", "uID"),
            // External
            #'characters'=>array(self::HAS_MANY, "Character", "uID"),
            #'gallery'=>array(self::HAS_ONE, "Gallery", "u_id"),
            #'blogPosts'=>array(self::HAS_MANY, "BlogPost", "u_id"),
            #'forumTopics'=>array(self::HAS_MANY, "ForumTopic", "u_id"),
            #'forumPosts'=>array(self::HAS_MANY, "ForumPost", 'u_id'),
        );
    }

    // Needs editing for user updates etc.
    public function beforeSave() {
        parent::beforeSave();
        if($this->isNewRecord || $this->scenario=="update") {
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
