<?php namespace BIRD3\Foundation\User\Conversations;

use BIRD3\Foundation\User\Entity as User;
use BIRD3\Support\Model\Validatable;
use Eloquent;

class Message extends Eloquent {
    use Validatable;

    protected $table = "user_pm_msg";
    public $timestamps = false;

    /**
     *  @int id PK          | Message ID
     *  @int conv_id        | The conversation this message belongs to
     *  @int from_ID        | Sender's uID
     *  @text body          | Message body
     *  @timestamp sent     | When was it sent
     */
     // Should get: created_at

    public function sender() {
        return $this->belongsTo(User::class, "from_id");
    }
    public function conversation() {
        return $this->belongsTo(Conversation::class, "conv_id");
    }

    // Rules for validation
    protected $rules = [
        "body" => "required"
    ];

    // Define automatic mutator/accessor
    protected $dates = ["sent"];
}
