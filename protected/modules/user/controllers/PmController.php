<?php class PmController extends Controller {

    use UserFilters;
    public function filters() {
        return [
            "must_be_logged_in + box, compose, show"
        ];
    }

    public function actionBox($ajax=false) {

    }

    public function actionCompose($to=null, $response=null) {
        $pm = new PrivateMessage;
        $conv = new PrivateConversation;
        $sm = Yii::app()->securityManager;

        // Pre-setting some
        $pm->to_ID = $to; # The user does not want weird numbers!

        if(isset($_POST["PrivateMessage"])) {
            // Obtain data
            $data = unserialize(base64_decode($sm->validateData($_POST["data"])));
            $response = $data["response"];

            // Make the PM
            $pm->attributes = $_POST["PrivateMessage"];
            $pm->from_ID = User::me()->id;
            $conv->response = $response;

            // Wait! $pm->to_ID is a userNAME.
            $u = User::model()->findByAttributes([
                "username"=>$pm->to_ID
            ]);
            if(is_null($u)) {
                // Uh oh.
                $pm->setError("to_ID", "User {$pm->to_ID} was not found!");
            } else {
                // Okay, we have the user, let's make it happen.
                // Subject and Message are already set.
                $pm->to_ID = $u->id;
                if($pm->save()) {
                    $conv->mID=$pm->id;
                    $conv->save();
                    return $this->redirect("/user/pm/box");
                }
            }
        } else {
            // Encrypt this data, its important.
            $data = $sm->hashData(base64_encode(serialize([
                "response"=>$response
            ])));
        }
        $this->render("compose", [
            "conv"=>$conv,
            "pm"=>$pm,
            "data"=>$data
        ]);
    }

    public function actionShow($mid) {

    }
}
