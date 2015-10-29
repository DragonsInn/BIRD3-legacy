<?php class PrivateConversationMembers extends CActiveRecord {

    public function relations() {
        return [
            "member"=>array(self::BELONGS_TO, "User", "user_id"),
            "convo"=>array(self::BELONGS_TO, "PrivateConversation", "conv_id")
        ];
    }

    public function tableName() {
        return "user_pm_conv_members";
    }

    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

}
