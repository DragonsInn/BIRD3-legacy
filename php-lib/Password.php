<?php class Password {
    static function hash($pwd) {
        # PHP uses the $2y$ prefix. It's all safe.
        return password_hash($pwd, PASSWORD_BCRYPT);
    }
    static function verify($pwd, $hash) {
        return password_verify($pwd, $hash);
    }
}
