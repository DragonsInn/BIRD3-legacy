<?php if($this->controller->displayLogin) { ?>
<div id="login">
    <h3>Login</h3>
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'=>'bird3-login',
        'enableAjaxValidation'=>false,
        'enableClientValidation'=>true,
        'action'=>$this->controller->createUrl("/user/user/login")
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
        <span class="input-group-btn">
            <?=CHtml::submitButton("Go!", array(
                "class"=>"btn btn-inverse"
            ))?>
        </span>
    </div>
    <?php $this->endWidget(); ?>
</div>
<?php } ?>
<div><pre>
    <?php var_dump(Yii::app()->user->isGuest); ?>
</pre></div>
