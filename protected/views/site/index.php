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
<div>
	Class: <?=get_class(Yii::app()->user)?>
</div>
<div>
	ID: <pre><?php var_dump(Yii::app()->user->getId()); ?></pre>
</div>
