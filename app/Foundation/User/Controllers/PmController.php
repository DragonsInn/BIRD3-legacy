<?php namespace BIRD3\Foundation\User\Controllers;

use BIRD3\Foundation\BaseController;

use BIRD3\Foundation\User\Conversations\Conversation;
use BIRD3\Foundation\User\Conversations\Message;

use Auth;
use Request;
use User;

use BIRD3\Backend\Log;

class PmController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->middleware("auth");
    }

    public function getBox() {
        $user = Auth::user();
        $convos = $user->conversationMemberships;
        return $this->render("User::pm.box",[
            "convos"=>$convos
        ]);
    }

    public function anyCompose($to=null) {
        $user = Auth::user();
        # Store errors here...
        $errors = [];

        // Prepare models.
        $convo = new Conversation;
        $convo->owner_id = $user->id;

        // N instances of PrivateConversationMembers + 1 UserUpdate
        $members = [];

        // A new message
        $msg = new Message;
        $msg->from_id = $user->id;

        // Handle POST
        if(Request::has("to") && Request::has("subject") && Request::has("body")) {
            $msg->body = Request::input("body");
            $convo->subject = Request::input("subject");

            # Pick up the user names
            $members = explode(",",Request::input("to"));
            foreach($members as $i=>$v) $members[$i]=trim($v);
            # Get the user id's.
            $realmembers = [];
            foreach($members as $m) {
                $u = User::where("username", $m)->first();
                if(!is_null($u)) $realmembers[] = $u;
                else $errors["To"] = "Username '$m' not found.";
            }

            // Try to save and such.
            if($convo->save()) {
                $msg->conv_id = $convo->id;
                if($msg->save()) {
                    # Add memberships. Add ourselves, too.
                    $realmembers[] = $user;
                    foreach($realmembers as $target) {
                        // Attach the members...
                        \BIRD3\Backend\Log::info("Adding: {$target->id} ({$target->username})");
                        $target
                            ->conversationMemberships()
                            ->attach($convo->id);
                    }
                    return redirect("/user/pm/box");
                }
            }
        }

        return $this->render("User::pm.compose",[
            "convo"=>$convo,
            "msg"=>$msg,
            "to"=>$to,
            "errors"=>array_merge_recursive(
                $errors, []
                #$convo->getErrors(),
                #$msg->getErrors()
            )
        ]);
    }

    public function anyConvo($conv_id) {
        $errors = [];
        $user = Auth::user();
        $convo = Conversation::findOrFail($conv_id);
        $msg = new Message();
        if(Request::has("pmReply")) {
            /*
                We need to make sure that the user that sends his message,
                isn't actually spoofing their destination conv_id.

                In Yii we used a HMAC. Wonder if Laravel has that too.
            */
            $to_conv_id = Request::input("pmReply.conv_id");
            $is_member = $user->conversationMemberships->contains($to_conv_id);
            if(!$is_member) {
                $errors["Validation"][] = "There was an error during transmission. Please try again.";
            } else {
                if($to_conv_id !== $conv_id) {
                    $errors["Validation"][] = "You tried to reply to an non-existant conversation.";
                } else {
                    $msg->conv_id = $to_conv_id;
                    $msg->from_id = $user->id;
                    $msg->body = Request::input("pmReply.body");
                    if(!$msg->save()) {
                        $errors = array_merge_recursive($errors, $msg->getErrors());
                    }
                }
            }
        }

        // Find and backtrack all the messages, the laraway.
        $messages = Message::where("conv_id", $conv_id)
                           ->orderBy("id","DESC")
                           ->get();

        if(Request::ajax()) {
            // This came from the /user/pm/box page, most likely.
            // For this case, we need to simplify things a little...
            $flatMessages = [];
            $error = false;
            $err = null;
            try {
                foreach($messages as $message) {
                    $flatMessages[] = [
                        "from"      => $message->sender->username,
                        "body"      => $message->body,
                        "is_read"   => $user->hasReadMessage($message)
                    ];
                }
            } catch(\Exception $e) {
                Log::error($e->getMessage());
                $error = true;
                $err = $e;
            }
            $resp = json_encode([
                "status" => $error ? "error" : "ok",
                "messages" => $flatMessages,
                "errorObj" => $err,
                "errors" => $errors
            ]);
            return $resp;
        } else {
            Log::info("In regular call");
            return $this->render("User::pm.convo",[
                "messages"=>$messages,
                "convo"=>$convo,
                "newMsg"=>$msg,
                "errors"=>$errors
            ]);
        }
    }

    // These methods should become AJAX methods.
    // And, they should support DELETE and other methods.

    public function getDrop($message_id) {
        $msg = Message::findOrFail($message_id);
        if($msg->from_id === Auth::user()->id) {
            // We are permitted to delete.
            // FIXME: More fine-grained check to utilize permissions.
            // Perms: CanDeleteAllConvoMessages, CanDeleteAllMessages (+CanSeeAllConvo?)
            $msg->delete();
            return redirect()->intended("/user/box");
        }
    }

    public function actionLeaveConvo($conv_id) {
        $user = Auth::user();
        $mship = $user->conversationMemberships();
        if($mship->contains($conv_id)) {
            $mship->detach($conv_id);
        }
        return redirect()->intended("/user/box");
    }
}
