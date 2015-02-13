<?php
/* @var $this SiteController */
/* @var $error array */
$this->layout = '//layouts/xynu';
$this->pageTitle='Error';
$this->breadcrumbs=array(
	'Error',
);
?>

<div id="speechbubble">
	<h1>Sorry my dear. I can't help you with this...</h1>
	<div class="error">
		<p>Code: <?=$code?></p>
		<p><?=CHtml::encode($type).": ".CHtml::encode($message)?></p>
		<!-- If the user is a dev, display dev infos. -->
	</div>
</div>
