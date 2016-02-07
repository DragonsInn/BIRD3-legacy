<?php namespace BIRD3\Foundation\User\Conversations;

use BIRD3\Foundation\User\Entity as User;
use Eloquent;

class ReadStatus extends Eloquent {
    protected $table = "user_pm_msg_readstatus";
    public $timestamps = false;
    protected $fillable = ["id","user_id","msg_id","isRead"];

    /**
     * @int id PK
     * @int msg_id FK  | The message this is refering to.
     * @int user_id FK | Link to a user.
     * @bool isRead    | Is it read?
     */

    public function message() {
        return $this->belongsTo(Message::class, "id");
    }
    public function user() {
        return $this->hasOne(User::class, "user_id");
    }
}
