<?php trait Duder {
    // This holds all cached user records. Key is ID
    private static $_users=[];

    public static function me($id=-1) {
        if($id == -1) {
            if(!Yii::app()->user->isGuest)
                // -1 means that we want ourself. Easy.
                // Using the internal BIRD3User::loadUser() function to get
                // the model that we want. Only works on non-guests.
                // The thing is that the model is LIKELY cached already.
                // So no need to re-get it.
                return Yii::app()->user->getModel();
            else {
                // A guest user does not have a database entry. So there is
                // not a real "me". Throw an exception if this is used
                // carelessly.
                ob_start();
                debug_print_backtrace();
                $txt = ob_get_contents();
                ob_end_clean();
                $msg = "A guest user has no DB entry.<br/>\n<pre>$txt</pre>";
                throw new CException($msg);
            }
        }
        // Caching is boss.
        if(!isset(self::$_users[$id])) {
            self::$_users[$id] = User::model()->findByPk($id);
        }
        // CActiveRecord::findByPk() returns NULL on ... no result.
        if(is_null(self::$_users[$id])) throw new CException("Unable to find user with ID $id");
        return self::$_users[$id];
    }

    public static function get($id) { return self::me($id); }

    private static function make_avatar_path($id, $as="file") {
        $cdn = Yii::app()->cdn;
        return join(DIRECTORY_SEPARATOR, [
            ($as == "file" ? $cdn->basePath : $cdn->baseUrl),
            "content", "avatars", $id
        ]);
    }

    public static function avatarUrl($id=-1) {
        if(Yii::app()->user->isGuest) {
            return "/nothing";
        }
        $user = self::me($id);
        $ext = $user->profile->avvie_ext;
        return join(".", [
            self::make_avatar_path($user->id, "url"),
            $ext
        ]);
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
