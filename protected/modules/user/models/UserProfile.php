<?php class UserProfile extends CActiveRecord {

    public static function model($className=__CLASS__) {
        return parent::model($className);
    }
    public function tableName() {
        return "{{user_profile}}";
    }

    /**
     * DB Structure
     * @int uID             | User ID this is linked to. Note, NO PK!
     * @string skype        | Skype name
     * @string steam        | Steam name
     * @string psn          | PSN name
     * @string xboxlife     | xbox life tag
     * @string facebook     | Facebook URL or username
     * @string twitter      | Twitter URL or username
     * @string furaffinity  | FurAffinity username
     * @string sofurry      | SoFurry username
     * @text about          | About the user. Optional
     * ? @text signature    | Signature for forum
     * @data avvie          | Image data representing the avvie.
     */
}
