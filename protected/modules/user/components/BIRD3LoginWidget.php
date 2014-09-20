<?php class BIRD3LoginWidget extends CWidget {

    public function run() {
        $this->render('LoginWidget', array(
            'model'=>new User("login")
        ));
    }
}
