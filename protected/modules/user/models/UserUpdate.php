<?php class UserUpdate extends CActiveRecord {

    public static function model($className=__CLASS__) {
        return parent::model($className);
    }
    public function tableName() {
        return "user_update";
    }
    public function primaryKey() { return "id"; }
    public function relations() {
        return array(
            "user"=>array(self::BELONGS_TO, "User", "tID")
        );
    }


    /**
     * @int id PK           | The ID of the update
     * @int tID             | The target user who gets it.
     * @int type            | One of this class' constants.
     * @int contentID       | The ID reffering to the content.
     * @timestamp inserted  | When this was inserted.
     */

     // The user got a comment on a submission
     const TGotComment  = 0;
     // One of the user's submissionw as favorited.
     const TGotFave     = 1;
     // One of the user's submissions was rated
     const TGotRate     = 2;
     // A user subscribed to this user's activities
     const TGotSub      = 3;
     // A media that this user faved was updated.
     const TMediaUpdate = 4;
     // A user that this user watches has posted new content
     const TNewContent  = 5;
}
