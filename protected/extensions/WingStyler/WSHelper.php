<?php class WSHelper {
    public function parse($url) {
        $realFile = Yii::app()->basePath."/..".$url;
        $fname = sha1_file($realFile).".css";
        $udir = dirname($url);
        $newUrl = $udir."/".$fname;
        
    }
}
