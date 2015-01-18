<?php
$this->pageTitle = str_replace($name, "_", " ");
$md = $this->viewPath."/".$name.".md";
$text = "";
if(file_exists($md)) {
    $hash = md5_file($md);
    $key = "docs-$hash";
    $cache = Yii::app()->cache;
    if($cache->offsetExists($key)) {
        $text = $cache->get($key);
    } else {
        $p = new Parsedown();
        $text = $p->text(file_get_contents($md));
        $cache->set($key, $text);
    }
    echo $text;
} else {
    throw new CHttpException(404, "Requested document not found.");
}
