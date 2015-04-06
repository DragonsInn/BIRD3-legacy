<?php class UserProfile extends CActiveRecord {

    public static function model($className=__CLASS__) {
        return parent::model($className);
    }
    public function tableName() {
        return "{{user_profile}}";
    }
    public function primaryKey() { return "uID"; }

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
     * @data avatar         | Image data representing the avvie.
     */

    public function relations() {
        return array(
            "user"=>array(self::BELONGS_TO, "User", "uID")
        );
    }

    public function attributeLabels() {
        return [
            "skype"=>'<i class="fa fa-skype"></i> Skype',
            "steam"=>'<i class="fa fa-steam"></i> Steam',
            "psn"=>'<span class="iconblock psn"></span> Playstation Network',
            "xboxlife"=>'<span class="iconblock xbl"></span> XBox Live',
            "facebook"=>'<i class="fa fa-facebook"></i> Facebook',
            "twitter"=>'<i class="fa fa-twitter"></i> Twitter',
            "sofurry"=>'<span class="iconblock sofurry"></span> SoFurry',
            "furaffinity"=>'FurAffinity'
        ];
    }

    public function rules() {
        return array(
            [
                "skype, steam, psn, xbloxlife, facebook, twitter, furaffinity, sofurry, about",
                "safe", "on"=>"update"
            ]
        );
    }
}
