<?php class UserUpdate extends CActiveRecord {

    public static function model($className=__CLASS__) {
        return parent::model($className);
    }
    public function tableName() {
        return "{{user_update}}";
    }

    /**
     * @int id PK           | The ID of the update
     * @int tID             | The target user who gets it.
     * @int type            | One of this class' constants.
     * @int contentID       | The ID reffering to the content.
     * @timestamp inserted  | When this was inserted.
     */

     const TGotComment  = 0;
     const TGotFave     = 1;
     const TGotSub      = 2;
     // More to come...

}
