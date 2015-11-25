<h2>Your settings</h2>
<p>
    You can change many settings concerning your profile or turning on and off a
    variety of offerings and services that the Dragon's inn offers.
</p>

<?=Form::open([
    'id'=>'bird3-settings',
    'name'=>'bird3-settings',
    "class"=>"form-horizontal"
])?>
<?php if(!function_exists("mkfg")) { function mkfg(array $data) { ?>
    <div class="form-group">
        <?php $key = $data["group"]."[".$data["field"]."]"; ?>
        <label for="<?=$key?>" class="col-sm-3 control-label">
            <?=$data["label"]?>
        </label>
        <div class="col-sm-5">
            <?=Form::{$data["func"]}($key, $data["model"]->{$data["field"]}, array(
                "placeholder"=>(isset($data["placeholder"]) ? $data["placeholder"] : ""),
                "class"=>"form-control",
            ))?>
        </div>
    </div>
<?php } } ?>
<div>
    <!-- FIXME: Error summaries -->
</div>

<div class="well">
    <h4>Account settings</h4>
    <div class="form-group">
        <?=Form::label("username", "Change your username", [
            "class"=>"col-sm-3 control-label",
        ])?>
        <div class="col-sm-5">
            <?=Form::text("username", $model->username, array(
                "placeholder"=>"...",
                "class"=>"form-control",
            ))?>
            <span class="help-block">
                <p>Change this with caution! This is the name you log in with, after all.</p>
            </span>
        </div>
    </div>

    <!--
    <div class="form-group">
        <?php /*Form::label($model, "password", array(
            "class"=>"col-sm-3 control-label",
            "label"=>"Change your password"
        ))*/ ?>
        <div class="col-sm-5">
            <?php /*Form::passwordField($model, "password", array(
                "class"=>"form-control",
            ))*/ ?>
            <span class="help-block">
                <p>Change this with caution! You may be able to recover it, but be careful still.</p>
            </span>
        </div>
    </div>
    -->


    <div class="form-group">
        <?=Form::label("email", "Change your E-Mail", array(
            "class"=>"col-sm-3 control-label",
        ))?>
        <div class="col-sm-5">
            <?=Form::text("email", $model->email, array(
                "placeholder"=>"i.am@your-screen.com",
                "class"=>"form-control",
            ))?>
            <span class="help-block">
                <p>
                    Make sure this is a valid E-Mail address, if you subscribe to the newsletter.
                </p>
            </span>
        </div>
    </div>

    <div class="form-group">
        <?=Form::label("adult_checkbox", "Enable adult content", array(
            "class"=>"col-sm-3 control-label",
        ))?>
        <div class="col-sm-5">
            <?=Form::checkbox("adult_checkbox", $model->settings, array(
                "class"=>"form-control",
            ))?>
        </div>
        <script>
            BIRD3.ready(function(){
                $("#adult_checkbox").click(function(ev){
                    var $t = $(this);
                    if($t.is(":checked")) {
                        var msg = "<h3>Warning!</h3>"
                                + "<p>You are about to enable adult content.</p>"
                                + "<p>Activating this option will enable any explicit content, "
                                + "including characters and other related content provided by "
                                + "other users. By deactivating this filter you are confirming "
                                + "that you are of-, or above 18 years of age.</p>"
                                + "<p>Click the <b>Yep</b> button if you want to continue and "
                                + "nope if you'd rather not.</p>"
                                + "<p>Thanks for your understanding,<br/>The Dragon's Inn staff.</p>";
                        window.question(msg, {}, function(res){
                            if(!res) {
                                // Disable by hand. Should have its own method.
                                $t.each(function(e){
                                    e.checked = false;
                                });
                            }
                        });
                    }
                });
            });
        </script>
    </div>

    <div class="form-group">
        <?=Form::label("newsletter", "Receive E-Mail updates from the Inn", array(
            "class"=>"col-sm-3 control-label",
        ))?>
        <div class="col-sm-5">
            <?=Form::checkbox("newsletter", $model->settings->newsletter, array(
                "class"=>"form-control",
            ))?>
            <span class="help-block">
                <p>
                    We don't send spam. But we send messages every here and then
                    when something important is happening.
                </p>
            </span>
        </div>
    </div>

    <div class="form-group">
        <?=Form::label("showEmail", "People can see your E-Mail on your profile page", array(
            "class"=>"col-sm-3 control-label",
        ))?>
        <div class="col-sm-5">
            <?=Form::checkbox("showEmail", $model->settings->showEmail, array(
                "class"=>"form-control",
            ))?>
        </div>
    </div>

    <hr>

    <h4>Profile settings</h4>
    <?php foreach($model->profile->attributeLabels() as $attr=>$disp): ?>
    <?php mkfg([
        "group"=>"UserProfile",
        "model"=>$model->profile,
        "field"=>$attr,
        "label"=>$disp,
        "func"=>"text"
    ]); ?>
    <?php endforeach; ?>
    <div class="form-group">
        <?=Form::label(
            "about",
            "Tell us something about yourself",
            ["class"=>"col-sm-3 control-label"]
        )?>
        <div class="col-sm-9">
            <?=Widget::MarkdownEditor($model->profile->about, [
                "attribute"=>"about",
                "autogrow"=>true
            ])?>
        </div>
    </div>
    <hr>
    <div class="center-block" style="width:200px;">
        <button type="submit" class="btn btn-success btn-lg">
            Save the changes
        </button>
    </div>
</div>

<?=Form::close()?>
