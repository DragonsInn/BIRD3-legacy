<?php trait Duder {
    // This holds all cached user records. Key is ID
    private static $_users=[];

    public static function guest() {
        $g = new User;
        $g->username = "Guest";
        $g->profile = new UserProfile;
        return $g;
    }

    public static function me($id=-1) {
        if($id == -1) {
            if(!Yii::app()->user->isGuest)
                // -1 means that we want ourself. Easy.
                // Using the internal BIRD3User::getModel() function to get
                // the model that we want. Only works on non-guests.
                // The thing is that the model is LIKELY cached already.
                // So no need to re-get it.
                return Yii::app()->user->getModel();
            else {
                // Return a fake record
                return self::guest();
            }
        }
        // Caching is boss.
        if(!isset(self::$_users[$id])) {
            $u = User::model()->findByPk($id);
            // CActiveRecord::findByPk() returns NULL on ... no result.
            if(is_null($u)) throw new CException("Unable to find user with ID $id");
            else            self::$_users[$id] = $u;
        }
        return self::$_users[$id];
    }

    public static function get($id) { return self::me($id); }

    private static function make_avatar_path($id, $ext, $as="file") {
        $cdn = Yii::app()->cdn;
        return join(DIRECTORY_SEPARATOR, [
            ($as == "file" ? $cdn->basePath : $cdn->baseUrl),
            "content", "avatars", "$id.$ext"
        ]);
    }

    public static function avatarUrl($id=-1) {
        $user = self::me($id);
        $ext = $user->profile->avvie_ext;
        if(!empty($ext)) {
            $path = self::make_avatar_path($user->id, $ext);
            if(file_exists($path)) {
                $hash = md5_file($path);
                return self::make_avatar_path($user->id, $ext, "url")."#$hash";
            } else {
                // Maybe a derp?
                $user->profile->avvie_ext = null;
                $user->profile->update();
                return self::avatarUrl($id);
            }
        } else {
            // Return generic image...
            return self::make_avatar_path("generic_avvie", "png", "url");
        }
    }

    public static function getHtml($id) {
        $user = self::get($id);
        $color = '';
        switch($user->superuser) {
            case User::R_ADMIN:
                $color = "lime";
            break;
            case User::R_MOD:
                $color = "orange";
            break;
            case User::R_VIP:
                $color = "aqua";
            break;
            case User::R_USER:
                $color = "white";
            break;
        }
        return CHtml::link(
            $user->username,
            ["/user/profile/view", "name"=>$user->username],
            ["style"=>"color:$color;"]
        );
    }
}
