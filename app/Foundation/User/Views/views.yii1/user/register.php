<?php
    $this->pageTitle = "Registration";
?>

<h1>Registration</h1>

<div class="row">
    <div class="col-md-6">
        <div class="well">
            <?php $form = $this->beginWidget('CActiveForm', array(
                'id'=>'bird3-login',
                'enableAjaxValidation'=>false,
                'enableClientValidation'=>true,
                'htmlOptions'=>array("class"=>"form-horizontal")
            )); ?>
            <?=$form->errorSummary($model,
                "The following errors were encountered"
            )?>
            <div class="form-group">
                <?=$form->label($model, "username", array(
                    "class"=>"col-sm-6 control-label",
                    "label"=>"Which username do you want?"
                ))?>
                <div class="col-sm-6">
                    <?=$form->textField($model, "username", array(
                        "placeholder"=>"...",
                        "required"=>"required",
                        "class"=>"form-control",
                    ))?>
                    <span class="help-block">
                        You can change this name later.
                    </span>
                </div>
            </div>
            <div class="form-group">
                <?=$form->label($model, "email", array(
                    "class"=>"col-sm-6 control-label",
                    "label"=>"What's your E-Mail?"
                ))?>
                <div class="col-sm-6">
                    <?=$form->emailField($model, "email", array(
                        "placeholder"=>"i.am@your-screen.com",
                        "required"=>"required",
                        "class"=>"form-control",
                    ))?>
                    <span class="help-block">
                        <p>
                            Make sure this is a valid E-Mail address. We will send a confirmation there.
                        </p>
                        <p>
                            Optionally, news updates and other cool stuff can be sent there!
                        </p>

                    </span>
                </div>
            </div>
            <div class="form-group">
                <?=$form->label($model, "password", array(
                    "class"=>"col-sm-6 control-label",
                    "label"=>"Choose a super secret password."
                ))?>
                <div class="col-sm-6">
                    <?=$form->passwordField($model, "password", array(
                        "required"=>"required",
                        "class"=>"form-control",
                    ))?>
                    <span class="help-block">
                        The password needs to be at least 6 characters long and at most 40.
                    </span>
                </div>
            </div>
            <div class="form-group">
                <?=$form->label($model, "repeat_password", array(
                    "class"=>"col-sm-6 control-label",
                    "label"=>"Repeat your password."
                ))?>
                <div class="col-sm-6">
                    <?=$form->passwordField($model, "repeat_password", array(
                        "required"=>"required",
                        "class"=>"form-control",
                    ))?>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-1">
                    <div class="checkbox">
                        <?php $cb = $form->checkbox($model, "read_tos"); ?>
                        <?=$form->label($model, "read_tos", array(
                            "label"=>"{$cb} I have read and accepted the Terms of Service."
                        ))?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-6">
                    <input type="submit" class="btn btn-primary btn-lg" value="Sign up!"/>
                </div>
            </div>
            <?php $this->endWidget(); ?>
        </div>
    </div>
    <div class="col-md-6">
        <h2>Terms of Service</h2>
        <p>Content... Gotta render some Markdown here.</p>
    </div>
</div>
