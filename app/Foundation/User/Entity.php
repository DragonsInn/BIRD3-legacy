<?php namespace BIRD3\Foundation\User;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use BIRD3\Support\Model\Validatable;
use BIRD3\Foundation\User\Conversations\Conversation;

// Alias
use Eloquent;

// Other models are in current namespace.
#use Profile, Settings, Permissions, ...;

// A dude and his dudy things.
// FIXME: Entity, user, or actually, Dude? Hrm...
class Entity
extends Eloquent
implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{

    // Make this class a validatable, authorizable thing.
    use Authenticatable, Authorizable, CanResetPassword;
    use Validatable;

    /**
     * Database Structure
     * @int id PK               | ID
     * @string username         | Username
     * @bcrypt password         | Hashed password
     * @string email            | User's registration email
     * @string activkey         | Used to verify email address
     * @int superuser           | Determines between usergroups.
     * @int status              | Inactive, Active, Banned
     * @bool developer          | If user is a dev or not.
     * @string role             | The role this user plays at the site.
     * @timestamp create_at     | Registration time
     * @timestamp lastvisit_at  | Last visited
     */

    const R_USER     =  0;
    const R_VIP      =  1;
    const R_MOD      =  2;
    const R_ADMIN    =  3;

    const S_INACTIVE =  0;
    const S_ACTIVE   =  1;
    const S_BANNED   =  2;

    // Eloquent settings
    protected $table = "users";
    protected $dates = ["create_at", "lastvisit_at"];
    public $timestamps = false;

    // Scopes
    // # Find by status: Active, Inactive, Banned
    public function scopeActive($q) { return $q->where("status",self::S_ACTIVE); }
    public function scopeInactive($q) { return $q->where("status",self::S_INACTIVE); }
    public function scopeBanned($q) { return $q->where("status", self::S_BANNED); }
    // # Find user by roles
    public function scopeAdmins($q) { return $q->where("superuser", self::R_ADMIN); }
    public function scopeMods($q) { return $q->where("superuser", self::R_MOD); }
    public function scopeVIP($q) { return $q->where("superuser", self::R_VIP); }
    public function scopeNormalUser($q) {
        // where( Not admin, vip or moderator )
    }

    // Validation rules
    // FIXME: Yii had scopes... Hm. o.o
    private $rules = [
        "username" => "required|unique",
        "email" => "required|email",
        "password" => "required|min:6|max:40"
    ];

    public function profile()           { return $this->hasOne(Profile::class, "uID"); }
    public function permissions()       { return $this->hasOne(Permissions::class, "id"); }
    public function settings()          { return $this->hasOne(Settings::class, "id"); }
    public function updates()           { return $this->hasMany(Update::class, "id"); }
    public function myConversations()   { return $this->hasMany(Conversation::class, "owner_id"); }
    public function conversationMemberships() {
        return $this->belongsToMany(
            Conversation::class,
            "user_pm_conv_members",
            "conv_id",
            "user_id"
        );
    }
    /*
        Missing relations:
            - Character     : hasMany
            - Gallery       : hasOne
            - Blog\Posts    : hasMany
            - Forum\Topic   : hasMany
            - Forum\Post    : hasMany
    */

    // # Authentificable utility function

    public function getRememberTokenName() {
        return "remember_me";
    }

    // # Utility functions

    // Convert this user's role to a string representation.
    public function roleToString() {
        switch($this->superuser) {
            case self::R_USER:  return "User";
            case self::R_VIP:   return "VIP";
            case self::R_MOD:   return "Mod";
            case self::R_ADMIN: return "Admin";
        }
    }

    // Determine if this user can or can not see adult stuff.
    // @class BIRD3\Support\Model\Rateable
    public function canSeeNSFWContent() {
        return $this->settings->adult;
    }
}
