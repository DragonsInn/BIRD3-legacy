<div id="login" class="fluid-container">
    <h3>Login</h3>
    <?=Form::open([
        "url"=>"/user/login",
        'id'=>'bird3-login',
        'class'=>'horizontal-form'
    ])?>
    <div class="input-group">
        <?=Form::text("username", null, [
            "placeholder"=>"Username",
            "required"=>"required",
            "class"=>"form-control",
            "aria-label"=>"User name"
        ])?>
    </div>
    <div class="input-group">
        <?=Form::password("password", [
            "placeholder"=>"Password",
            "required"=>"required",
            "class"=>"form-control",
            "aria-label"=>"Password"
        ])?>
        <span class="input-group-btn">
            <button type="submit" class="btn btn-default">
                ->
            </button>
        </span>
    </div>
    <?=Form::close()?>
</div>
<hr>
<div>
    <div class="btn-group-vertical" role="group" aria-label="Register and password reset" style="width:100%">
        <a href="/user/register" class="btn btn-info">
            <p>Not registered yet?</p>
            <p>Create a new account now!</p>
        </a>
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
