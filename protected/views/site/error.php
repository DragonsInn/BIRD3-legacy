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
		<p>In: <?=$file?><b>(</b><?=$line?><b>)</b></p>
		<?php if(!Yii::app()->user->isGuest && Yii::app()->user->getDeveloper()): ?>
		<h2>Stacktrace</h2>
		<ul><?php
			foreach(explode("\n", $trace) as $file=>$mtd) {
				echo "<li>$mtd</li>";
			}
		?></ul>
		<?php endif; ?>
	</div>
</div>
