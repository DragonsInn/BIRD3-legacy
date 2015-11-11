<?php namespace BIRD3\Foundation\User;

use Eloquent;

class Update extends Eloquent {

    protected $table = "user_update";

    /**
     * @int id PK           | The ID of the update
     * @int tID             | The target user who gets it.
     * @int type            | One of this class' constants.
     * @int contentID       | The ID reffering to the content.
     * @timestamp inserted  | When this was inserted.
     */

     // The user got a comment on a submission
     const GotComment  = 0;
     // One of the user's submissionw as favorited.
     const GotFave     = 1;
     // One of the user's submissions was rated
     const GotRate     = 2;
     // A user subscribed to this user's activities
     const GotSub      = 3;
     // A media that this user faved was updated.
     const MediaUpdate = 4;
     // A user that this user watches has posted new content
     const NewContent  = 5;

     public function user() {
         return $this->belongsTo(User::class, "tID");
     }

     protected $dates = ["inserted"];
}
