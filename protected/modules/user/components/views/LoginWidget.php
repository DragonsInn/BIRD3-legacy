<div id="login">
    <h3>Login</h3>
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'=>'bird3-login',
        'enableAjaxValidation'=>false,
        'enableClientValidation'=>true,
        'action'=>$this->controller->createUrl("/user/user/login")
    )); ?>
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
            <button type="submit" class="btn btn-default">
                <i class="fa fa-arrow-right"></i>
            </button>
        </span>
    </div>
    <?php $this->endWidget(); ?>
</div>
<hr>
<div>
    <div class="btn-group-vertical" role="group" aria-label="Register and password reset" style="width:100%">
        <button class="btn btn-info" style="width:100%">
            <p>Not registered yet?</p>
            <p>Create a new account now!</p>
        </button>
        <button class="btn btn-default" style="width:100%">
            Forgot your password?
        </button>
    </div>
</div>
<div style="height:10px"></div>
<p>
    Registering for the Dragon's Inn is completely free! We won't force you to look at ads you wont like or
    &nbsp;anything like that.
</p>
<p>
    Join and meet new friends, buddies to hang out with and enjoying roleplays, art and other cool things together.
    &nbsp;Even chatter about the newest games or anything you want!
</p>
<p>
    The Dragon's Inn is an open-minded community with the aim to provide a place for people to talk, relax,
    &nbsp;roleplay, share their art, music and ideas with one another and most importantly, to meet new
    &nbsp;friends. Friendship is very important to us. So come and join and be ours!
</p>
