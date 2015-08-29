<?php class User extends CActiveRecord {

    // That enables workaround functions.
    // Not quite how traits work, but a good way to do it.
    use Duder;

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
     * @string role             | The role this user plays at the site.
     * @timestamp create_at     | Registration time
     * @timestamp lastvisit_at  | Last visited
     */

    const R_USER     =  0;
    const R_VIP      =  1;
    const R_MOD      =  2;
    const R_ADMIN    =  3;
    public $superuser=self::R_USER;

    const S_INACTIVE =  0;
    const S_ACTIVE   =  1;
    const S_BANNED   =  2;
    public $status=self::S_INACTIVE;

    public static function model($className=__CLASS__) {
        return parent::model($className);
    }
    public function tableName() {
        return "users";
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

    // This is used only for the case below
    public $repeat_password;
    public $read_tos;

    public function rules() {
        return array(
            # Register
            array('username, email, read_tos', 'required', 'on'=>'register'),
            array('password, repeat_password', 'required', 'on'=>'register'),
            # These buddies will only be needed a few times.
            array('password, repeat_password', 'length', 'min'=>6, 'max'=>40),
            array('password', 'compare', 'compareAttribute'=>'repeat_password', "on"=>"register"),
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
            # Static relations
            'profile'=>array(self::HAS_ONE, 'UserProfile', 'uID'),
            'updates'=>array(self::HAS_MANY, "UserUpdate", "tID"),
            'permissions'=>array(self::HAS_MANY, "UserPermissions", "uID"),
            'settings'=>array(self::HAS_ONE, "UserSettings", "id"),
            # Dynamic relations
            "convos"=>array(
                self::MANY_MANY,
                "PrivateConversation",
                "tbl_user_pm_conv_members(user_id,conv_id)"
            ),
            "my_convos"=>array(
                self::HAS_MANY,
                "PrivateConversation",
                "owner_id"
            ),
            // External
            // All the users' characters.
            #'characters'=>array(self::HAS_MANY, "Character", "uID"),
            // All of their posted media.
            #'gallery'=>array(self::HAS_ONE, "Gallery", "u_id"),
            // Their blog
            #'blogPosts'=>array(self::HAS_MANY, "BlogPost", "u_id"),
            // Forum
            #'forumTopics'=>array(self::HAS_MANY, "ForumTopic", "u_id"),
            #'forumPosts'=>array(self::HAS_MANY, "ForumPost", 'u_id'),
        );
    }

    private $totallyNew=false;
    public function beforeSave() {
        if(parent::beforeSave() != false) {
            if($this->isNewRecord && $this->scenario=="register") {
                $this->password = md5($this->password);
                $this->create_at = time();
            }
            if($this->scenario=="update") {
                $this->lastvisit_at = time();
            }
            if($this->isNewRecord) {
                $this->totallyNew = true;
                $this->lastvisit_at = time();
            }
            return true;
        } else return false;
    }
    public function afterSave() {
        if($this->totallyNew) {
            // Create relations
            $profile = new UserProfile;
            $profile->uID = $this->id;
            $profile->save();
            $settings = new UserSettings;
            $settings->id = $this->id;
            $settings->save();
            $perms = new UserPermissions;
            $perms->id = $this->id;
            $perms->save();
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
            $this->_idendity->authenticate();
            if($this->_idendity->errorCode == BIRD3UserIdendity::ERROR_PASSWORD_INVALID) {
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
            if($this->_idendity->id == NULL) {
                throw new CException("An error occured while retriving your ID. The login failed.");
            }
            $rememberMe=3600*24*30;
            Yii::app()->user->login($this->_idendity, $rememberMe);
            return true;
        } else {
            switch($this->_idendity->errorCode) {
                case BIRD3UserIdendity::ERROR_USERNAME_INVALID:
                    throw new CException("Fatal error: Username invalid in 2nd step.");
                case BIRD3UserIdendity::ERROR_PASSWORD_INVALID:
                    throw new CException("Fatal error: Password invalid in 2nd step.");
            }
            return false;
        }
    }

}
