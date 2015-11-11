<?php namespace BIRD3\Foundation\User\Providers;

use Illuminate\Contracts\Auth\UserProvider;
use BIRD3\Foundation\User\Entity as User;
use BIRD3\Support\Password;

class BIRD3UserProvider implements UserProvider {

    // Get a user by ID
    // @returns BIRD3\Foundation\User\Entity
    public function retrieveById($identifier){
        return User::findOrFail($identifier);
    }

    // Find a user by his RememberMe token and ID.
    // Likely to see if we can re-login. o.o
    public function retrieveByToken($identifier, $token) {
        return User::where("remember_me", $token)
                   ->findOrFail($identifier);
    }

    // Update the RememberMe token
    // We should have one in the BIRD3 Users table.
    public function updateRememberToken(User $user, $token) {
        $user->remember_me = $token;
        return $user->update();
    }

    // Find the user by using their User and Password (array)
    public function retrieveByCredentials(array $credentials) {
        return User::where("username", $credentials["username"])
                   ->where("password", Password::hash($credentials["password"]))
                   ->get();
    }

    // Check if the user and pass match up.
    public function validateCredentials(UserContract $user, array $credentials) {
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
