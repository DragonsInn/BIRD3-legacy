<?php use Phinx\Migration\AbstractMigration;
class UserInit extends AbstractMigration {

    public function change() {
        $donull = ["null"=>true];
        $users = $this->table("users");
        $users
            ->addColumn("username","string",["limit"=>20])
            ->addColumn("password","string",["limit"=>61])
            ->addColumn("email","string",["limit"=>128])
            # Generated during registration
            ->addColumn("activkey","string",["limit"=>128])
            # Admin, Mod, VIP, User
            ->addColumn("superuser","integer")
            # Active, Inactive, Banned
            ->addColumn("status","integer")
            # Can see debug information
            ->addColumn("developer","boolean")
            # Will be listed in the Credits page
            ->addColumn("supporter","boolean",["default"=>false])
            # Timestamps for registration and last visit
            ->addColumn("create_at","integer")
            ->addColumn("lastvisit_at","integer",$donull)

            ->addIndex(["username", "email"], ["unique"=>true])
        ->create();

        $userProfile = $this->table("user_profile", ["id"=>false, "primary_key"=>"uID"]);
        $userProfile
            ->addColumn("uID","integer")
            ->addColumn("about","text",$donull)
            ->addColumn("avvie_ext","string",["limit"=>5]+$donull);

        # Socialness
        foreach([
            "skype", "steam",
            "psn", "xboxlife",
            "twitter", "facebook",
            "sofurry", "furaffinity"
        ] as $service) $userProfile->addColumn($service, "string", ["limit"=>255]+$donull);

        $userProfile
            ->addForeignKey("uID", "users", "id", ["delete"=>"CASCADE", "update"=>"CASCADE"])
        ->create();

        $userSettings = $this->table("user_settings",["id"=>false, "primary_key"=>"id"]);
        $userSettings
            ->addColumn("id","integer")
            # Enable adult content
            ->addColumn("adult","boolean",["default"=>false])
            # Enable newsletter via email
            ->addColumn("newsletter","boolean",["default"=>true])
            # The user's profile can be seen in public, i.e. non-registered members.
            ->addColumn("public","boolean",["default"=>true])
            # Show the user's eMail to the public.
            ->addColumn("showEmail","boolean",["default"=>false])

            ->addForeignKey("id", "users", "id", ["delete"=>"CASCADE", "update"=>"CASCADE"])
        ->create();

        $userPerms = $this->table("user_permissions", ["id"=>false, "primary_key"=>"id"]);
        $userPerms->addColumn("id", "integer");
        $fields = [
            # User can contribute to the public blog.
            "publicBlog",
            # This user can add, modify and delete jobs. Admins always can.
            "manageJobs",
            # User can create, edit and delete places. Admins always can.
            "editPlaces",
            # User can edit OTHER people's chars/media. Includes deletion. Mods and Admins, always.
            "editChars", "editMedia",
            # User can edit/delete forum related stuff
            "editFPosts", "editFTopics", "editFSections",
            # This user may turn on or off Developer status on other users.
            "editDev",
            # This user can broadcast a message through the chat using /wall.
            "canBroadcast"
        ];
        foreach($fields as $f)
            $userPerms->addColumn($f,"boolean",["default"=>false]);

        $userPerms
            ->addForeignKey("id", "users", "id", ["delete"=>"CASCADE", "update"=>"CASCADE"])
        ->create();

        // Private messaging.  Now it gets complicated. :)

        /*
         User conversations

             ONE Conversation
             HAS MANY Conversation Members
                 HAS MANY Users
             HAS ONE Private Message
         */
        $convMembers = $this->table("user_pm_conv_members",["id"=>false, "primary_key"=>["user_id","conv_id"]]);
        $convMembers
            ->addColumn("user_id", "integer")
            ->addColumn("conv_id", "integer")
        ->create();
        $conv = $this->table("user_pm_conv");
        $conv
            ->addColumn("owner_id","integer")
            ->addColumn("subject","string",["limit"=>255,"default"=>"(No subject)"])
        ->create();
        $msg = $this->table("user_pm_msg");
        $msg
            ->addColumn("conv_id","integer")
            ->addColumn("from_id","integer")
            ->addColumn("body","text")
            ->addColumn("sent","integer")
        ->create();

        # FK setup for PM
        $convMembers
            ->addForeignKey("user_id", "users", "id", ["delete"=>"NO_ACTION", "update"=>"NO_ACTION"])
            ->addForeignKey("conv_id", "user_pm_conv", "id", ["delete"=>"CASCADE", "update"=>"NO_ACTION"])
        ->save();

        // Other stuff
        $this->table("user_sub", ["id"=>false])
            # The subscribing user
            ->addColumn("sID", "integer")
            # The target user
            ->addColumn("tID", "integer")

            ->addForeignKey("sID", "users", "id", ["delete"=>"CASCADE", "update"=>"NO_ACTION"])
            ->addForeignKey("tID", "users", "id", ["delete"=>"CASCADE", "update"=>"NO_ACTION"])
        ->create();

        $this->table("user_update")
            # The user who gets this
            ->addColumn("tID", "integer")
            # the kind of update. A numeric identifier
            ->addColumn("type","integer")
            # The ID of the referenced content
            ->addColumn("contentID","integer")
            # Relate
            ->addForeignKey("tID", "users", "id", ["delete"=>"CASCADE", "update"=>"NO_ACTION"])
        ->create();
    }

}
