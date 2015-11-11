<?php namespace BIRD3\Foundation\User;

use Eloquent;

class Permissions extends Eloquent {
    protected $table= "user_permissions";
    }

    /**
     *  @int id PK/FK       | Reffers to the user
     *  @bool publicBlog    | The user's blog is public, merged into the front page.
     *
     *  Discuss who can do these
     *  @bool manageJobs    | User can manage jobs in the hotel
     */

     public function user() {
         return $this->belongsTo(User::class, "id");
     }

}
