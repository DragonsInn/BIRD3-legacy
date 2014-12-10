<?php class Character extends CActiveRecord {

    public static function model($className=__CLASS__) {
        return parent::model($className);
    }
    public function tableName() {
        return "{{charabase}}";
    }

    /**
     * DB Structure
     * @int cID PK
     * @int uID SK
     * @int created_at
     * @int edited_at
     * @int last_updated | Used by the system.
     * @int last_played | Chat updates this
     * @int importance | Main, Casual
     * @int status | New, Unpalyed, Abandoned
     * @int visibility | Private, Community, Public
     * @bool adult
     * @int style | Pic-Only, Simple, Detailed, Advanced
     *
     * Basic but important
     * @string name
     * @string nickName
     * @string species
     * @int sex | Male, Female, Herm, Maleherm, Cuntboi, Shemale, Gendershifter, Genderless
     * @int orientation | Straight, Bi, Gay, Lesbo, Pan, Omni, Not interested
     * @text personality
     * @date(string 20) birthday
     * @string birthPlace
     *
     * Details
     * @string height
     * @string weight
     * @string eye_c
     * @string eye_s
     * @string hair_c
     * @string hair_s
     * @string hair_l
     * @int bodyType | Twink, Casual, Perfect, Chubby, Fat
     * @text appearance | previous appearance stuff into one.
     *
     * Spiritual
     * @int spirit_status | Alive, Dead, God, Immortal
     * @int spirit_condition | must pick up from bird2.
     * @int spirit_alignment | Good, Neutral, Bad
     * @int spirit_sub_alignment | Lawful, Middle, Chaotic
     * @int spirit_type | Light, Dark, Twilight
     * @string/data spirit_death_date
     * @string spirit_death_place
     * @string spirit_death_cause
     *
     * Literature
     * @text history
     * @text likes
     * @text dislikes
     * @text addit_desc
     *
     * Adult
     * @int dom_sub | Dominant, Submissive, Powerbottom, Switch
     * @text preferences | Fuses previous addit_adult and preferences
     *
     * Image page
     * @text artistNote
     *
     * Other, Misc
     * @string other_title | Appears as separate section. Is a big text.
     * @text other
     * @text css | CSS code.
     * @int theme_type | YouTube, Audio URL, Video URL.
     * @string theme | url, youtube link
     */

    /**
     * Sections:
     *
     * Basic
     *      - Name
     *      - Nick Name
     *      - Species
     *      - Sex
     *      - Orientation
     *      - Is adult?
     *      - Visibility
     *      - Importance
     *      - Style
     *
     * Birth and Death
     *      - Birthday
     *      - Place of birth
     *      - Death date
     *      - Place of death
     *      - Cause of death
     *
     * Alignment
     *      - Status
     *      - Condition
     *      - Alignment
     *      - Sub-Alignment
     *      - Type
     *
     * Story
     *      - History
     *      - Likes
     *      - Dislikes
     *      - Additional Description
     *
     * Appearance
     *      - Eye color, style
     *      - Hair color, style, length
     *      - height, Weight
     *
     * Adult
     *      - Behavior
     *      - Preferences
     */


     // Methods to produce and return lists or type names / id's.
     // Sort of labeling the stuff, yknow o.o

     # Importance
     const IP_MAIN = 1;
     const IP_CASUAL = 0;
     public static function listImportance() {
         return array(
             self::IP_MAIN => "Main",
             self::IP_CASUAL => "Casual"
         );
     }
     public static function Importance($i) { return self::listImportance()[$i]; }

     # status
     const ST_NEW = 0;
     const ST_OK = 1;
     const ST_ABANDONED = 2;
     public static function listStatus() {
         return array(
             self::ST_NEW => "New",
             self::ST_OK => "Ok",
             self::ST_ABANDONED => "Abandoned"
         );
     }
     public static function Status($i) { return self::listStatus()[$i]; }

     # Visibility
     const VS_PRIVATE = 0;
     const VS_COMMUNITY = 1;
     const VS_PUBLIC = 2;
     public static function listVisibility() {
         return array(
             self::VS_PRIVATE => "Private",
             self::VS_COMMUNITY => "Community Only",
             self::VS_PUBLIC => "Public"
         );
     }
     public static function Visibility($i) { return self::listVisibility()[$i]; }

     # Style
     const SY_PIC_ONLY = 0;
     const SY_SIMPLE = 1;
     const SY_DETAILED = 2;
     const SY_ADVANCED = 3;
     public static function listStyle() {
         return array(
             self::SY_PIC_ONLY => "Pic-Only",
             self::SY_SIMPLE => "Simple",
             self::SY_DETAILED => "Detailed",
             self::SY_ADVANCED => "Advanced"
         );
     }
     public static function Style($i) { return self::listStyle()[$i]; }

     # Sex
     const SEX_MALE = 0;
     const SEX_FEMALE = 1;
     const SEX_HERM = 2;
     const SEX_MALEHERM = 3;
     const SEX_CUNTBOI = 4;
     const SEX_SHEMALE = 5;
     const SEX_SHIFTER = 6;
     const SEX_NONE = 7;
     public static function listSex() {
         return array(
             self::SEX_MALE => "Male",
             self::SEX_FEMALE => "Female",
             self::SEX_HERM => "Hermaphrodite",
             self::SEX_MALEHERM => "Maleherm",
             self::SEX_CUNTBOI => "Cuntboi",
             self::SEX_SHEMALE => "Shemale",
             self::SEX_SHIFTER => "Gendershifter",
             self::SEX_NONE => "Genderless"
         );
     }
     public static function Sex($i) { return self::listSex()[$i]; }

     # Orientation
     const OR_STRAIGHT = 0;
     const OR_BI = 1;
     const OR_LESBIAN = 2;
     const OR_GAY = 3;
     const OR_PAN = 4;
     const OR_OMNI = 5;
     const OR_NOGO = 6;
     const OR_UNKNOWN = 7;
     public static function listOrientation() {
         return array(
             self::OR_STRAIGHT => "Straight",
             self::OR_BI => "Bisexual",
             self::OR_LESBIAN => "Lesbian",
             self::OR_GAY => "Gay",
             self::OR_PAN => "Pansexual",
             self::OR_OMNI => "Omnisexual",
             self::OR_NOGO => "Not interested",
             self::OR_UNKNOWN => "Unknown"
         );
     }
     public static function Orientation($i) { return self::listOrientation()[$i]; }

     # Bodytype
     const BT_SKINNY = 0;
     const BT_FEMININE = 1;
     const BT_ATHLETIC = 2;
     const BT_TYPICAL = 3;
     const BT_CHUBBY = 4;
     const BT_MUSCULAR = 5;
     const BT_HERCULEAN = 6;
     public static function listBodytype() {
         return array(
            self::BT_SKINNY => "Skinny",
            self::BT_FEMININE => "Feminine",
            self::BT_ATHLETIC => "Athletic",
            self::BT_TYPICAL => "Typical",
            self::BT_CHUBBY => "Chubby",
            self::BT_MUSCULAR => "Muscular",
            self::BT_HERCULEAN => "Herculean"
         );
     }
     public static function Bodytype($i) { return self::listBodytype()[$i]; }

     # Spirit status
     const SS_ALIVE = 0;
     const SS_DEAD = 1;
     const SS_GOD = 2;
     const SS_IMMORTAL = 3;
     public static function listSpiritStatus() {
         return array(
            self::SS_ALIVE => "Alive",
            self::SS_DEAD => "Dead",
            self::SS_GOD => "God",
            self::SS_IMMORTAL => "Immortal"
         );
     }
     public static function SpiritStatus($i) { return self::listSpiritStatus()[$i]; }

     # Spirit condition
     const SC_HEALTHY_HAPPY = 0;
     const SC_HEALTHY_SICK = 1;
     const SC_DEPRESSED = 2;
     const SC_ALONE = 3;
     const SC_ANGRY = 4;
     const SC_MIXED = 5;
     public static function listSpiritCondition() {
         return array(
            self::SC_HEALTHY_HAPPY => "Happy & Healthy",
            self::SC_HEALTHY_SICK => "Not very happy",
            self::SC_DEPRESSED => "Depressed",
            self::SC_ALONE => "Alone",
            self::SC_ANGRY => "Angry",
            self::SC_MIXED => "Mixed"
         );
     }
     public static function SpiritCondition($i) { return self::listSpiritCondition()[$i]; }

     # Spirit Alignment
     const SA_GOOD = 1;
     const SA_NEUTRAL = 2;
     const SA_BAD = 3;
     public static function listSpiritAlignment() {
         return array(
             self::SA_GOOD => "Good",
             self::SA_NEUTRAL => "Neutral",
             self::SA_BAD => "Bad"
         );
     }
     public static function SpiritAlignment($i) { return self::listSpiritAlignment()[$i]; }

     # Spirit Sub-Alignment
     const SAS_LAWFUL = 0;
     const SAS_MIDDLE = 1;
     const SAS_CHAOTIC = 2;
     public static function listSpiritSubAlignment() {
         return array(
             self::SAS_LAWFUL => "Lawful",
             self::SAS_MIDDLE => "Middle",
             self::SAS_CHAOTIC => "Chaotic"
         );
     }
     public static function SpiritSubAlignment($i) { return self::listSpiritSubAlignment()[$i]; }

     # Spirit Type
     const ST_LIGHT = 0;
     const ST_DARK = 1;
     const ST_TWILIGHT = 2;
     public static function listSpiritType() {
         return array(
             self::ST_LIGHT => "Lawful",
             self::ST_DARK => "Middle",
             self::ST_TWILIGHT => "Chaotic"
         );
     }
     public static function SpiritType($i) { return self::listSpiritType()[$i]; }

     # Sexual behavior
     const SB_DOM = 0;
     const SB_SUB = 1;
     const SB_SWITCH = 2;
     const SB_NEUTRAL = 3;
     const SB_POWERBOTTOM = 4; # Likes to take with dominant behavior
     const SB_M_DOM = 5; # mostly dominant. When hit with right trigger, goes submissive
     const SB_M_SUB = 6; # Vice versa from above
     public static function listSexualBehaviour() {
         return array(
             self::SB_DOM => "Dominant",
             self::SB_SUB => "Submissive",
             self::SB_SWITCH => "Switch",
             self::SB_NEUTRAL => "Neutral",
             self::SB_POWERBOTTOM => "Powerbottom",
             self::SB_M_DOM => "Mostly dominant",
             self::SB_M_SUB => "Mostly submissive"
         );
    }
    public static function SexualBehavior($i) { return self::listSexualBehaviour()[$i]; }

    // set the defaults...
    public function initializeDefaults() {
        // This is rather typical, really.
        if(!$this->importance)      $this->importance = self::IP_CASUAL;
        // Adult + Private = Nobody sees it, ever, unless its set otherwise.
        if(!$this->visibility)      $this->visibility = self::VS_PRIVATE;
        // We always assume this profile is adult.
        if(is_null($this->adult))   $this->adult = true;
        // Lazy butts are lazy, yo.
        if(!$this->style)           $this->style = self::SY_PIC_ONLY;
        // System default
        if(!$this->status)          $this->status = self::ST_NEW;
    }
    public function beforeSave() {
        if($this->isNewRecord) {
            $this->uID = Yii::app()->user->id;
            $this->created_at = time();
            $this->last_updated = time();
        } else {
            // This record is being updated.
            $this->edited_at = time();
            $this->last_updated = time();
        }
    }

    // now the funb egins!
    public function scopes() {
        return array(
            # Importance
            'main'     =>array('condition'=>'importance='.self::IP_MAIN  ),
            'casual'   =>array('condition'=>'importance='.self::IP_CASUAL),
            # Status
            'new'      =>array( 'condition'=>'status='.self::ST_NEW      ),
            'playing'  =>array( 'condition'=>'status='.self::ST_OK       ),
            'abandoned'=>array( 'condition'=>'status='.self::ST_ABANDONED),
        );
    }

    public function relations() {
        return array(
            'user'=>array(self::BELONGS_TO, "User", "uID"),
            #'pics'=>array(self::HAS_MANY, "CharacterPicture", "oID"),
            #'forms'=>array(self::HAS_MANY, "CharacterForm", "cID"),

            // One character can be linked to many share-users.
            // But this one needs work.
            #'sharedWith'=>array(self::HAS_MANY, "CharacterShare", "cID"),

            // One character, has many relationships. In Yii's way, they are
            // two 1-n/n-1 relationships. So we link it that way. Hopefuly itll work...
            #'isInRelationWith'=>array(self::HAS_MANY, "CharacterRelationship", "s_id"),
            #'isRelatedWith'=>array(self::BELONGS_TO, "CharacterRelationship", "t_id"),

            // This, however, must be a true one.
            #'associations'=>array(self::MANY_MANY, "CharacteRAssociation", "tbl_charabase_AssocRel(cID,aID)")
        );
    }

    public function attributeLabels() {
        return array(
            "sex"=>"Gender",
            "style"=>"Form style",
            "spirit_death_date"=>"Death date",
            "spirit_death_cause"=>"Cause of death",
            "spirit_death_place"=>"Place of death",
            "birthPlace"=>"Place of birth"
        );
    }

    public function search() {
        $crit = new CDbCriteria();
        $crit->compare("cID",$this->id);
        // This one is a bit odd.
        $ctit->compare("uID", User::model()->findByAttributes(
            array("username"=>$this->username)
        )->id);
        $crit->compare("importance", $this->importance);
        $crit->compare("status", $this->status);
        $crit->compare("adult", $this->adult);
        $crit->compare("visibility", $this->visibility);
        $crit->compare("LOWER(name)","%".$this->name."%", true);
        $crit->compare("LOWER(nickName)","%".$this->nickName."%", true);
        $crit->compare("LOWER(species)","%".$this->species."%", true);
        $crit->compare("sex", $this->sex);
        return new CActiveDataProvider($this, array(
            'criteria'=>$crit,
        ));
    }

    // Bomb inocming!
    public function rules() {
        return array(
            # Content settings
            array("importance, visibility, style, adult", "required"),
            # Character idendity
            array("name, species, sex, orientation", "required"),
            # Scenarios aka. styles.
            # Style 1: Pic Only. Personality is required FOR REAOSNS.
            array(
                "name, species, sex, orientation",
                "required", "on"=>self::SY_PIC_ONLY
            ),
            # Style 2: Simple (detailed advanced)
            array(
                "birthday, birthPlace, height, weight, history, likes, dislikes, addit_desc",
                "safe", "on"=>self::SY_SIMPLE
            ),
            # Style 3: Detailed
            array(
                "eye_c, eye_s, hair_c, hair_s, hair_l, bodyType, appearance",
                "safe", "on"=>self::SY_DETAILED
            ),
            # Style 4: Advanced. Everything must be safe.
            array(
                "spirit_status, spirit_condition, spirit_alignment, spirit_sub_alignment, "
                ."spirit_type, spirit_death_date, spirit_death_place, spirit_death_cause, "
                ."other_title, other, css, theme_type, theme",
                "safe", "on"=>self::SY_ADVANCED
            ),
            # This stuff is -always- save.
            array("personality, dom_sub, preferences, artistNote", "safe")
        );
    }


}
