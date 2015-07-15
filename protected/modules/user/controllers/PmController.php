<?php class PmController extends Controller {

    use UserFilters;
    public function filters() {
        return [
            "must_be_logged_in + box, compose, show"
        ];
    }

    public function actionBox($page=1) {
        $convoRelations = PrivateConversationMembers::model()->findAllByAttributes([
            "user_id" => User::me()->id
        ]);
        if($convoRelations == NULL) {
            # Short circuit this.
            return $this->render("emptyBox");
        }
        $convos = [];
        foreach($convoRelations as $rel) {
            $convos[] = $rel->convo;
        }
        $pg = new Voodoo\Paginator();
        #$pg->setUrl($_SERVER["REQUEST_URI"], "/user/pm/box/page/{:num}");
        $pg->setPage($page);
        $count = count($convos);
        $pg->setItems($count, 20);
        $pg->setPrevNextTitle(
            '<i class="fa fa-caret-left" aria-hidden="true"></i> Prev',
            'Next <i class="fa fa-caret-right" aria-hidden="true"></i>'
        );
        $limit = $pg->getPerPage();
        $offset = $pg->getStart();
        $myConvos = PrivateConversation::model()->findAll([
            "limit"=>$limit,
            "offset"=>$offset
        ]);
        $pages = $pg->toArray();
        $this->render("box", ["pages"=>$pages, "convos"=>$myConvos]);
    }

    public function actionCompose($to=null) {
        # Store errors here...
        $errors = [];

        // The conversation
        $convo = new PrivateConversation;
        $convo->owner_id = User::me()->id;
        $members = []; # N instances of PrivateConversationMembers + 1 UserUpdate

        // A new message
        $msg = new PrivateMessage;
        $msg->from_id = User::me()->id;

        if(isset($_POST["PrivateMessage"]) && isset($_POST["PrivateConversation"])) {
            $msg->attributes = $_POST["PrivateMessage"];
            $convo->subject = $_POST["PrivateConversation"]["subject"];

            # Pick up the user names
            $members = explode(",",$_POST["to"]);
            foreach($members as $i=>$v) $members[$i]=trim($v);
            # Get the user id's.
            $realmembers = [];
            foreach($members as $m) {
                $u = User::model()->findByAttributes(["username"=>$m]);
                if(!is_null($u)) $realmembers[] = $u;
                else $errors["To"] = "Username '$m' not found.";
            }

            // Try to save and such.
            if($convo->save()) {
                $msg->conv_id = $convo->id;
                if($msg->save()) {
                    # Add memberships. Add ourselves, too.
                    $realmembers[] = User::me();
                    $worked = true;
                    foreach($realmembers as $target) {
                        $membership = new PrivateConversationMembers();
                        $membership->conv_id=$convo->id;
                        $membership->user_id=$target->id;
                        if(!$membership->save()) {
                            $errors["Other"] =
                                "Unable to assign {$target->username} to this conversation.";
                            $worked = false;
                            break;
                        }
                    }
                    if($worked) {
                        die("worked");
                    }
                }
            }
        }
        $this->render("compose",[
            "convo"=>$convo,
            "msg"=>$msg,
            "to"=>$to,
            "errors"=>array_merge_recursive(
                $errors,
                $convo->getErrors(),
                $msg->getErrors()
            )
        ]);
    }

    public function actionShow($conv_id) {
        $errors = [];
        $convo = PrivateConversation::model()->findByPk($conv_id);
        $msg = new PrivateMessage();
        if(isset($_POST["PrivateMessage"])) {
            # User has sent a reply, so put it through.
            $scm = Yii::app()->securityManager;
            $to_conv_id = $scm->validateData($_POST["conv_id"]);
            if(!$to_conv_id) {
                $errors["Validation"][]= "There was an error during transmission. Please try again.";
            } else {
                if($to_conv_id !== $conv_id) {
                    $errors["Validation"][]="You tried to reply to an non-existant conversation.";
                } else {
                    $msg->conv_id = $to_conv_id;
                    $msg->from_id = User::me()->id;
                    $msg->body = $_POST["PrivateMessage"]["body"];
                    if(!$msg->save()) {
                        $errors = array_merge_recursive($errors, $msg->getErrors());
                    }
                }
            }
        }
        // Find and back-travel all messages
        $crit = new CDbCriteria;
        $crit->order = "id DESC";
        $messages = PrivateMessage::model()->findAllByAttributes([
            "conv_id"=>$conv_id
        ], $crit);
        $this->render("show",[
            "messages"=>$messages,
            "convo"=>$convo,
            "newMsg"=>$msg,
            "errors"=>$errors
        ]);
    }

    // These methods should become AJAX enabled in the future.
    // Idea: Overwrite CController:redirect and return json_encode(["status"=>"OK"]) instead.

    public function actionDeleteMessage($message_id) {
        $msg = PrivateMessage::model()->findByPk($message_id);
        if($msg->from_id === User::me()->id) {
            // We are permitted to delete.
            $msg->delete();
            $this->redirect(Yii::app()->request->urlReferrer);
        }
    }

    public function actionLeaveConvo($conv_id) {
        $convo = PrivateConversation::model()->findByPk($conv_id);
        $members = $convo->members;
        $isMember = false;
        foreach($members as $i=>$user) {
            // make sure this user is a member.
            if($user->id === User::me()->id) {
                unset($members[$i]);
                $isMember = true;
                break;
            }
        }
        if($isMember) {
            // Load and kill that membership!
            $membership = PrivateconversationMembers::model()->findByPk([
                "user_id"=>User::me()->id,
                "conv_id"=>$convo->id
            ]);
            if(!is_null($membership)) {
                $membership->delete();
            }
        } else {
            throw new CException("You are no member of this convo!");
        }
        // Wait! Is this convo empty now?
        if(count($members) == 0) {
            // Get rid of the convo entirely!
            $convo->delete();
        }
        $this->redirect(Yii::app()->request->urlReferrer);
    }
}
