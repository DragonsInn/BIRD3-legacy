<?php namespace BIRD3\Foundation\User\Providers;

use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use BIRD3\Foundation\User\Entity as User;
use BIRD3\Support\Password;
use BIRD3\Backend\Log;


class BIRD3UserProvider implements UserProvider {

    // Get a user by ID
    // @returns BIRD3\Foundation\User\Entity
    public function retrieveById($identifier){
        Log::info("retrieveById");
        return User::findOrFail($identifier);
    }

    // Find a user by his RememberMe token and ID.
    // Likely to see if we can re-login. o.o
    public function retrieveByToken($identifier, $token) {
        Log::info("retrieveByToken");
        return User::where("remember_me", $token)
                   ->where("id", $identifier)
                   ->first();
    }

    // Update the RememberMe token
    // We should have one in the BIRD3 Users table.
    public function updateRememberToken(Authenticatable $user, $token) {
        Log::info("updateRememberToken");
        $user->remember_me = $token;
        return $user->update();
    }

    // Find the user by using their User and Password (array)
    public function retrieveByCredentials(array $credentials) {
        Log::info("retrieveByCredentials");
        $user = User::where("username", $credentials["username"])
                    ->first();

       if(empty($user)) {
           // Maybe a legacy user?
           $user = User::where("username", $credentials["username"])
                      ->where("password", md5($credentials["password"]))
                      ->first();
           if(!empty($user)) {
               // Update the md5 hash to bcrypt.
               $user->password = Password::hash($credentials["password"]);
               $user->update();
           }
       }

       return $user;
    }

    // Check if the user and pass match up.
    public function validateCredentials(Authenticatable $user, array $credentials) {
        Log::info("validateCredentials");
        if(
            $user->username == $credentials["username"]
            && Password::verify($credentials["password"], $user->password)
        ) {
            // This works just fine. Typical BIRD3 user.
            return true;
        } else if(
            $user->username == $credentials["username"]
            && md5($credentials["password"]) == $user->password
        ) {
            \BIRD3\Backend\Log::info("BIRD2 user detected.");
            // A BIRD2 user who didn't convert yet. Take care of that, first.
            $newHash = Password::hash($credentials["password"]);
            $user->password = $hash;
            $user->update();
            return true;
        } else {
            // Nope de la train.
            return FALSE;
        }
    }

}
