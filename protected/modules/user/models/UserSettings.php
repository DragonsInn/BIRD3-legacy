<?php class UserSettings extends CActiveRecord {

    public static function model($className=__CLASS__) {
        return parent::model($className);
    }
    public function tableName() {
        return "{{user_settings}}";
    }
    public function primaryKey() { return "uID"; }
    public function relations() {
        return array(
            "user"=>array(self::BELONGS_TO, "User", "uID")
        );
    }


    /**
     *  @int uID PK/FK      | The user to which this is assigned.
     *  @bool adult         | The user can see adult content.
     *  @bool newsletter    | The user wants newsletters.
     *  @bool public        | The user's profile is public
     *  @bool showEmail     | The user's email address is shown in the profile
     */

    // Stuff
    public function rules() {
        return array(
            ["adult, newsletter, public, showEmail", "safe", "on"=>"update"]
        );
    }

}
