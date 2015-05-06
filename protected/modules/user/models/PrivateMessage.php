<?php class PrivateMessage extends CActiveRecord {
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }
    public function tableName() {
        return "{{user_pm_msg}}";
    }
    public function primaryKey() { return "id"; }

    /**
     *  @int id PK          | Message ID
     *  @int conv_id        | The conversation this message belongs to
     *  @int from_ID        | Sender's uID
     *  @text body          | Message body
     *  @timestamp sent     | When was it sent
     */

     public function relations() {
         return [
             "sender"=>array(self::BELONGS_TO, "User", "from_id"),
             "conv"=>array(SELF::BELONGS_TO, "PrivateConversation", "conv_id")
         ];
     }

    public function rules() {
        return [ ["body", "required"] ];
    }

    public function attributeLabels() {
        return [
            "body"=>"Message content"
        ];
    }

    public function beforeSave() {
        if($this->isNewRecord) {
            $this->sent = time();
        }
        parent::onBeforeSave();
        return true;
    }
}
