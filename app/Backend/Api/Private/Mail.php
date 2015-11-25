<?php class BIRD3 extends CApplicationComponent {

    private static $ch=NULL;
    private static function getChannel() {
        if(self::$ch==NULL)
            self::$ch = new ARedisChannel("BIRD3");
        return self::$ch;
    }

    public static function emitRedis($event, $data) {
        $channel = self::getChannel();
        return $channel->publish(json_encode([
            "name"=>$event,
            "data"=>$data
        ]));
    }

    public static function mail(array $data) {
        return self::emitRedis("mail.send", $data);
    }

}
