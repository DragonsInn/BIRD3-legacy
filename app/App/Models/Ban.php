<?php class Ban extends CActiveRecord {
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }
    public function tableName() {
        return "bans";
    }
    public function primaryKey() { return "id"; }

    /** http://serverfault.com/a/419919
     *  @int id PK           | ID of the ban
     *  @int endTime         | The time(stamp) when the ban ends. If due, remove/revoke.
     *                       | Uses a NodeJS worker to run over these from here to there.
     *  @bool infinite       | The ban is absolute. It will never retire.
     *  @int uID             | If the ban is connected to user, this is set.
     *  @varchar reason      | A reason description why the ban was issued.
     *  @varchar ip          | The target IP
     *  @varchar fingerprint | A browser fingerprint
     */

    // Generates an output message for the ban page.
    public function message() {
        if($this->uID == -1) {
            $msg = "<p>You were listed as a banned client.</p>";
        } else {
            $user = User::model()->findByPk($this->uID);
            $msg = "<p>".$user->username.", you were banned because:</p>";
            $msg.= "<p>".$this->reason."</p>";
        }
        $msg.= "<p>End of ban: ".($this->infinite
            ? "Never"
            : date("jS F Y @ g:i A (T)", $this->endTime)
        ).".</p>";

        return $msg;
    }

    // Ban the current user and redirect them.
    // AJAXChat needs a bit of special handling there...
    // AJAXChat runs mainly via Node, but a PHP implementation
    // will be needed.
    public static function enforce($time, $reason="", $redirect=true) {
        $ip = $_SERVER["REMOTE_ADDR"];
        $uID = -1;
        $bantime=null;
        $infinite = false;
        if(!Yii::app()->user->isGuest) {
            $uID = Yii::app()->user->id;
        }
        if($time != false) {
            $bantime = strtotime($time);
        } else {
            $infinite = true;
        }
        $ban = new self();
        $ban->ip = $ip;
        $ban->uID = $uID;
        $ban->reason = $reason;
        $ban->infinite = $infinite;
        $ban->endTime = $bantime;
        $ban->save();
        # Hush to the front page, so we can display an error.
        if($redirect) $this->redirect("/");
    }

    // These constants help for the following functions
    const BY_USER = 0;
    const BY_IP = 1;

    public static function isBanned($in, $t) {
        $ban=null;
        switch($t) {
            case self::BY_USER:
                $ban = self::model()->findByAttributes(["uID"=>$in]);
                break;
            case self::BY_IP:
                $ban = self::model()->findByAttributes(["ip"=>$in]);
                break;
            default:
                throw new CException("No valid constant given!");
        }
        return !is_null($ban);
    }
}
