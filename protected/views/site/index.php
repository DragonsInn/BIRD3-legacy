<?php
/* @var $this SiteController */

$this->pageTitle="Home";

#$this->tabbar="WHADAFU1";
#$this->leftSide="some app links";
?>

<div class="row">
	<h1 class="col-sm-12">Latest Characters...</h1>
</div>
<div class="row">
	<div class="col-md-6">
		this is div 1
	</div>
	<div class="col-md-6">
		This is div 2
	</div>
</div>

<?php /* $this->widget("BIRD3Tabbar",array(
	#"brand"=>"Meep",
	"entries"=>array(
		"Intro"=>array("#Intro"),
		"Litterature"=>array("#Litterature"),
		"Pers"=>array("#Personality")
	)
)); */ ?>

<div id="tab-content" class="row">
  <div id="Intro" class="col-md-12">
	<h2>Character intro</h2>
  </div>
  <div id="Litterature" class="col-md-12">
	<h2>Literature that nobody reads</h2>
  </div>
  <div id="Personality" class="col-md-12">
	<h2>Personality. Yeah, sex.</h2>
  </div>
</div>
