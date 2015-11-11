<?php namespace BIRD3\Foundation\User\Providers;

// Laravel
use Illuminate\Support\ServiceProvider;

// BIRD3
use BIRD3\Foundation\User\Providers\BIRD3UserProvider;
use BIRD3\Foundation\User\Entity as User;
use BIRD3\Foundation\User\Profile;
use BIRD3\Foundation\User\Settings;
use BIRD3\Foundation\User\Permissions;
use BIRD3\Foundation\User\Conversations\Message as ConvoMessage;
use BIRD3\Support\Password;

// Facades
use Validator;

class UserServiceProvider extends ServiceProvider {
    public function boot() {
        // Add the auth driver...
        $this->app["auth"]->extend("BIRD3User", function(){
            return new BIRD3UserProvider(new User);
        });

        // Add a validation rule that checks equality of fields
        Validator::extend("equals", function($attr, $val, $args, $validator){
            return Input::get($args[0]) == $value;
        });

        // Attach events to the models
        // # User
        User::creating(function($model){
            // Set password hash
            $model->password = Password::hash($model->password);
            // Set time
            $model->create_at = time();
            // Set defaults
            $model->superuser = User::R_USER;
            $model->status = User::S_INACTIVE;
        });
        User::created(function($model){
            $model->profile()->save(new Profile);
            $model->permissions()->save(new Permissions);
            $model->settings()->save(new Settings);
        });

        // # Conversations
        ConvoMessage::creating(function($model){
            $model->sent = time();
    });
    }

    public function register() {

    }
}
