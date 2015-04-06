<?php class PrivateConversation extends CActiveRecord {
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }
    public function tableName() {
        return "{{user_pm_conv}}";
    }
    public function primaryKey() { return "id"; }

    /**
     *  @int PK id      | Conversation ID
     *  @int mID        | The message being sent
     *  @int response   | If this message was a response, this is a mID.
     *  @int composed   | When the message was composed
     */

    public function relations() {
        return [
            "message"=>array(self::HAS_ONE, "PrivateMessage", "mID"),
        ];
    }

    // This gets the response.
    public function getResponse() {
        if($this->response != -1) {
            return self::model()->findByAttributes([
                "mID"=>$this->response
            ]);
        } else {
            return NULL;
        }
    }

    // Gets all messages to this point and downwards.
    public function getWhole() {
        $msgs = [$this->message];
        $curr = $this;
        while(1) {
            $curr = $curr->getResponse();
            if(!is_null($curr)) {
                $msgs[] = $curr->message;
            } else break;
        }
        # Messages are now stored upside-down. (Newest first).
        return $msgs;
    }
}
