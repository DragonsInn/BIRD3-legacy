<?php class ClientCache extends CApplicationComponent {
    const STRING = 1;
    const FILE = 2;
    public function makeHeaders($data, $type=self::STRING, $options=[]) {
        $etag = ($type == self::STRING ? md5($data) : md5_file($data));
        $date = new DateTime( (isset($options["time"]) ? $options["time"] : "+1 month") );
        $now = new DateTime();
        $etagHeader=(isset($_SERVER['HTTP_IF_NONE-MATCH'])
            ? $_SERVER['HTTP_IF_NONE-MATCH']
            : false
        );
        if($etagHeader && $etagHeader == $etag) {
            header("HTTP/1.1 304 Not Modified");
            Yii::app()->end();
        }
        if(!headers_sent()) {
            header("Pragma: cache");
            header("Cache-control: public, must-validate, max-age=".(60*60*24*30), true);
            #header("Date: $now", true);
            #header("Expires: $date", true);
            header("Etag: $etag", true);
        }
    }
}
