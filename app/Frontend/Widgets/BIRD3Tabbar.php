<?php class BIRD3Tabbar extends CWidget {
    public $brand="";
    public $entries=array();
    public $tabContainer="#tab-content";
    public $updateHash=true;

    public function init() {
        $this->render("BIRD3TabbarView");
    }
}
