<?php class PrivateMessage extends CActiveRecord {
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }
    public function tableName() {
        return "{{user_pm}}";
    }
    public function primaryKey() { return "id"; }

    /**
     *  @int id PK          | Message ID
     *  @int from_ID        | Sender's uID
     *  @int to_ID          | Acceptor's uID
     *  @varchar subject    | Subject
     *  @text message       | Message body
     */

     public function relations() {
         return [
             # Redundant, but should keep it the Yii way.
             "owner"=>array(self::BELONGS_TO, "User", "sID"),
             # Actual relations
             "sender"=>array(self::HAS_ONE, "User", "sID"),
             "acceptor"=>array(self::HAS_ONE, "User", "tID"),
         ];
     }

    public function getConvo() {
        return PrivateConversation::model()->findByAttributes([
            "mID"=>$this->id
        ]);
    }

    public function rules() {
        return [ ["to_ID, subject, message", "required"] ];
    }

    public function attributeLabels() {
        return [
            "to_ID"=>"To",
            "subject"=>"Subject",
            "message"=>"Message"
        ];
    }
}
