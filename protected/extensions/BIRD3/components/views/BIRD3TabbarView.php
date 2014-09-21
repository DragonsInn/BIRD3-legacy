<?php
// @var $c The controller
$c = $this->controller;

$brand = (!empty($this->brand)?'<h4 class="navbar-text">'.$this->brand.'</h4>':"");
$c->tabbar = '<!-- Tab bar begin -->
<nav class="navbar navbar-soft" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#tabbarOptions">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            '.$brand.'
        </div>

        <div class="collapse navbar-collapse" id="tabbarOptions" role="tablist">
            <ul class="nav navbar-nav" id="etabList">'."\n";

// Here is the actual logic.
$tb = "                <li>";
$te = "</li>\n";
foreach($this->entries as $name=>$options) {
    $url = $options[0];
    unset($options[0]);
    $c->tabbar .= $tb;
    $c->tabbar .= CHtml::link($name,$url,$options);
    $c->tabbar .= $te;
}

$c->tabbar .='</ul>
        </div>
    </div>
</nav>
<!-- End of tabbar -->';

// Now to the JS stuff...
Yii::app()->clientScript->registerScript(
    "BIRD3Tabbar__".$this->tabContainer,
    "$('body').easytabs({
        panelContext: $('".$this->tabContainer."'),
        tabs: '#etabList > li'
    });"
);
