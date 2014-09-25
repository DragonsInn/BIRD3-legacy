<?php
/* @var $this SiteController */

$this->pageTitle="Home";

#$this->tabbar="WHADAFU1";
#$this->leftSide="some app links";
?>

<h1>Latest Characters...</h1>
<div>
	<div style="width:50%; position:relative; float: left;">
		this is div 1
	</div>
	<div style="width:50%; position:relative; float: left;">
		This is div 2
	</div>
</div>
<?php $this->widget("BIRD3Tabbar",array(
	#"brand"=>"Meep",
	"entries"=>array(
		"Intro"=>array("#Intro"),
		"Litterature"=>array("#Litterature"),
		"Pers"=>array("#Personality")
	)
)); ?>
<div id="tab-content">
  <div id="Intro">
	<h2>Character intro</h2>
  </div>
  <div id="Litterature">
	<h2>Literature that nobody reads</h2>
  </div>
  <div id="Personality">
	<h2>Personality. Yeah, sex.</h2>
  </div>
</div>

<?php Yii::app()->session["foo"]="bar"; ?>

<div><pre><?php print_r(Yii::app()->session); ?></pre></div>
<div><pre><?php print_r($_COOKIE); ?></pre></div>
