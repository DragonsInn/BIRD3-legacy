<?php
use Colors\Color;
class UsersCommand extends CConsoleCommand {

    public function actionCreate($name, $status, $password, $dev=true) {
        $c = new Color();
        $user = new User("register");
        $user->username = $name;
        $user->password = $user->repeat_password = $password;
        $user->email = uniqid()."@command.line";
        $user->superuser = constant("User::R_".strtoupper($status));
        $user->developer=$dev;
        $user->read_tos=true;
        $user->activkey="0";
        if($user->save()) {
            echo $c("Done!")->green().PHP_EOL;
        } else {
            echo $c("Failed:")->red().PHP_EOL;
            foreach($user->getErrors() as $n=>$e) {
                echo "# $n\n";
                foreach($e as $msg) {
                    echo "  - ".$c($msg)->red().PHP_EOL;
                }
            }
        }
    }
    public function actionInit() { /*...*/ }

}
