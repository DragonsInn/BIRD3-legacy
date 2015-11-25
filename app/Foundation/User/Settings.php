<?php namespace BIRD3\Foundation\User;

use Eloquent;

class Settings extends Eloquent {

    protected $table = "user_settings";

    /**
     *  @int uID PK/FK      | The user to which this is assigned.
     *  @bool adult         | The user can see adult content.
     *  @bool newsletter    | The user wants newsletters.
     *  @bool public        | The user's profile is public
     *  @bool showEmail     | The user's email address is shown in the profile
     */

    public function user() {
        return $this->belongsTo(User::class, "id");
    }

    protected $fillable = [
        "adult", "newsletter", "public", "showEmail"
    ];

}
