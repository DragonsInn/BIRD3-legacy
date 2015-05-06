<?php class PrivateConversation extends CActiveRecord {
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }
    public function tableName() {
        return "{{user_pm_conv}}";
    }
    public function primaryKey() { return "id"; }

    /**
     *  @int PK id      | Conversation ID
     *  @int FK owner_id| The one who made this
     *  @string subject | Conversation subject
     */

    public function relations() {
        return [
            "messages"=>array(self::HAS_MANY, "PrivateMessage", "conv_id"),
            "owner"=>array(self::BELONGS_TO, "User", "id"),
            "members"=>array(
                self::MANY_MANY,
                "User",
                "tbl_user_pm_conv_members(user_id,conv_id)",
            ),
        ];
    }

    public function addMember($user_id) {
        $this->members[] = $user_id;
    }
}
