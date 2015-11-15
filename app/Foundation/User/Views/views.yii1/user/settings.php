<h2>Your settings</h2>
<p>
    You can change many settings concerning your profile or turning on and off a
    variety of offerings and services that the Dragon's inn offers.
</p>

<?php $form = $this->beginWidget('CActiveForm', array(
    'id'=>'bird3-settings',
    'htmlOptions'=>[
        'name'=>'bird3-settings',
        "class"=>"form-horizontal"
    ]
)); ?>
<?php function mkfg($form, array $data) { ?>
    <div class="form-group">
        <?=$form->label($data["model"], $data["field"], array(
            "class"=>"col-sm-3 control-label",
            "label"=>$data["label"]
        ))?>
        <div class="col-sm-5">
            <?=$form->{$data["func"]}($data["model"], $data["field"], array(
                "placeholder"=>(isset($data["placeholder"]) ? $data["placeholder"] : ""),
                "class"=>"form-control",
            ))?>
        </div>
    </div>
<?php } ?>
<div>
    <?=$form->errorSummary($model,
        "Errors with entries for account settings:"
    )?>
    <?=$form->errorSummary($model->settings,
        "Errors with some settings:"
    )?>
    <?=$form->errorSummary($model->profile,
        "Errors with some profile entries:"
    )?>
</div>

<div class="well">
    <h4>Account settings</h4>
    <div class="form-group">
        <?=$form->label($model, "username", array(
            "class"=>"col-sm-3 control-label",
            "label"=>"Change your username"
        ))?>
        <div class="col-sm-5">
            <?=$form->textField($model, "username", array(
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
        <?=$form->label($model, "password", array(
            "class"=>"col-sm-3 control-label",
            "label"=>"Change your password"
        ))?>
        <div class="col-sm-5">
            <?=$form->passwordField($model, "password", array(
                "class"=>"form-control",
            ))?>
            <span class="help-block">
                <p>Change this with caution! You may be able to recover it, but be careful still.</p>
            </span>
        </div>
    </div>
    -->


    <div class="form-group">
        <?=$form->label($model, "email", array(
            "class"=>"col-sm-3 control-label",
            "label"=>"Change your E-Mail"
        ))?>
        <div class="col-sm-5">
            <?=$form->emailField($model, "email", array(
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
        <?=$form->label($model->settings, "adult", array(
            "class"=>"col-sm-3 control-label",
            "label"=>"Enable adult content",
            "for"=>"adult_checkbox"
        ))?>
        <div class="col-sm-5">
            <?=$form->checkbox($model->settings, "adult", array(
                "class"=>"form-control",
                "id"=>"adult_checkbox",
                "uncheckValue"=>null
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
        <?=$form->label($model->settings, "newsletter", array(
            "class"=>"col-sm-3 control-label",
            "label"=>"Receive E-Mail updates from the Inn"
        ))?>
        <div class="col-sm-5">
            <?=$form->checkbox($model->settings, "newsletter", array(
                "class"=>"form-control",
                "uncheckValue"=>null
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
        <?=$form->label($model->settings, "showEmail", array(
            "class"=>"col-sm-3 control-label",
            "label"=>"People can see your E-Mail on your profile page"
        ))?>
        <div class="col-sm-5">
            <?=$form->checkbox($model->settings, "showEmail", array(
                "class"=>"form-control",
                "uncheckValue"=>null
            ))?>
        </div>
    </div>

    <hr>

    <h4>Profile settings</h4>
    <?php foreach($model->profile->attributeLabels() as $attr=>$disp): ?>
    <?php mkfg($form, [
        "model"=>$model->profile,
        "field"=>$attr,
        "label"=>$disp,
        "func"=>"textField"
    ]); ?>
    <?php endforeach; ?>
    <div class="form-group">
        <?=$form->label($model->profile, "about", array(
            "class"=>"col-sm-3 control-label",
            "label"=>"Tell us something about yourself"
        ))?>
        <div class="col-sm-9">
            <?php $this->widget("BIRD3MarkdownEditor",[
                "context"=>$form,
                "model"=>$model->profile,
                "attribute"=>"about",
                "autogrow"=>true
            ]); ?>
        </div>
    </div>
    <hr>
    <div class="center-block" style="width:200px;">
        <button type="submit" class="btn btn-success btn-lg">
            Save the changes
        </button>
    </div>
</div>

<?php $this->endWidget(); ?>
