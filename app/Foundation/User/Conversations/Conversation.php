<?php namespace BIRD3\Foundation\User\Conversations;

use Eloquent;

use BIRD3\Foundation\User\Entity as User;
#use Message;

class Conversation extends Eloquent {
    protected $table = "user_pm_conv";
    public $timestamps = false;

    /**
     *  @int PK id            | Conversation ID
     *  @int FK owner_id      | The one who made this
     *  @string subject       | Conversation subject
     *  @timestamp created_at | The date of creation.
     */

    public function messages() {
        return $this->hasMany(Message::class, "conv_id");
    }
    public function owner() {
        return $this->belongsTo(User::class, "owner_id");
    }

    /*
        convos.id --> conv_id > user_pm_conv_members < user_id <-- users
    */
    public function members() {
        return $this->belongsToMany(
            User::class,
            "user_pm_conv_members",
            "conv_id",
            "user_id"
        );
    }
}
