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
     *  @int sID        | Sender's uID
     *  @int rID        | Acceptor's uID
     *  @int mID        | The message being sent
     *  @int response   | If this message was a response, this is a mID.
     *  @int composed   | When the message was composed
     */

    public function relations() {
        return [
            # Redundant, but should keep it the Yii way.
            "owner"=>array(self::BELONGS_TO, "User", "sID"),
            # Actual relations
            "sender"=>array(self::HAS_ONE, "User", "sID"),
            "acceptor"=>array(self::HAS_ONE, "User", "tID"),
            "message"=>array(self::HAS_ONE, "PrivateMessage", "mID"),
        ];
    }
}
