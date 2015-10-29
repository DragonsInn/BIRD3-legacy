<?php class BIRD3Menu extends CWidget {
    public $links;
    public $id="menu-tabs";

    public function run() {
        $this->render("BIRD3MenuView");
    }
}
