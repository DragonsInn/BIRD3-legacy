<?php namespace BIRD3\Foundation\User;

use Eloquent;

class Profile extends Eloquent {

    protected $table = "user_profile";

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

    public function user() {
        return $this->belongsTo(User::class, "id");
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

    protected $fillable = [
        "skype", "steam", "psn",
        "xbloxlife", "facebook", "twitter",
        "furaffinity", "sofurry", "about"
    ];
}
