<h2>Login</h2>
<?php $form = $this->beginWidget('CActiveForm', array(
    'id'=>'bird3-login',
    'enableAjaxValidation'=>false,
    'enableClientValidation'=>true,
    'action'=>$this->createUrl("/user/user/login")
)); ?>

<div><?=$form->errorSummary($model)?></div>

<div class="input-group">
    <?=$form->textField($model, "username", array(
        "placeholder"=>"Username",
        "required"=>"required",
        "class"=>"form-control",
        "aria-label"=>"User name"
    ))?>
</div>
<div class="input-group">
    <?=$form->passwordField($model, "password", array(
        "placeholder"=>"Password",
        "required"=>"required",
        "class"=>"form-control",
        "aria-label"=>"Password"
    ))?>
</div>

<?=CHtml::submitButton("Log in", array(
    "class"=>"btn btn-inverse"
))?>

<?php $this->endWidget(); ?>

<pre><?php print_r($_POST); ?></pre>
