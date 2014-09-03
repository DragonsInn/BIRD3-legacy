<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle='Error';
$this->breadcrumbs=array(
	'Error',
);
?>

<div style="text-align: center;">
	<h1 style="font-size: 70px;">O.-.O</h1>
	<h2>Error <?php echo $code; ?></h2>
	<div class="error">
		<?php echo CHtml::encode($message); ?>
	</div>
</div>
