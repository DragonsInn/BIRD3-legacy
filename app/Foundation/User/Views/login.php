<h2>Login</h2>
<?=Form::open([
    'url'=>'/user/login',
    'id'=>'bird3-login'
])?>

<div class="input-group">
    <?=Form::text("username", null, array(
        "placeholder"=>"Username",
        "required"=>"required",
        "class"=>"form-control",
        "aria-label"=>"User name"
    ))?>
</div>
<div class="input-group">
    <?=Form::password("password", array(
        "placeholder"=>"Password",
        "required"=>"required",
        "class"=>"form-control",
        "aria-label"=>"Password"
    ))?>
</div>

<?=Form::submit("Log in", array(
    "class"=>"btn btn-inverse"
))?>

<?=Form::close()?>
