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
     *  @varchar subject    | Subject
     *  @text message       | Message body
     */
}
