<?php
    use HtmlObject\Image;
    use HtmlObject\Link;
?>
<h3>Create a conversation</h3>
<p>
    You can create a converation with one OR many people. Separate the usernames by a comma in order
    to reply. send it to multiple people.
</p>

<?php #if(!empty($errors)): ?>
    <ul>
        <?php foreach($errors as $section=>$msgs): ?>
            <li><?=$section?></li>
            <ul>
            <?php foreach($msgs as $msg): ?>
                <li><?=$msg?></li>
            <?php endforeach; ?>
            </ul>
        <?php endforeach; ?>
    </ul>
<?php #endif; ?>

<div class="media">
    <div class="media-left">
        <?=Image::create(User::avatarUrl())
            ->class("media-object")
            ->alt("My avatar")
        ?>
    </div>
    <div class="media-body">
        <?php
            # Sneak-form!
            $form = $this->beginWidget('CActiveForm', array(
                'id'=>'bird3-create-convo',
                'action'=>$this->createUrl("compose"),
                'enableAjaxValidation'=>false,
                'enableClientValidation'=>true,
                'htmlOptions'=>[
                    'name'=>'bird3-reply-to-msg',
                    #"class"=>"form-horizontal"
                ]
            ));
            echo $form->textField($convo, "subject", [
                "class"=>"form-control",
                "placeholder"=>"Subject..."
            ]);
            echo CHtml::textField("to",$to,[
                "class"=>"form-control",
                "placeholder"=>"To..."
            ]);
            $this->widget("BIRD3MarkdownEditor",[
                "context"=>$form,
                "model"=>$msg,
                "useWell"=>false,
                "attribute"=>"body",
                "autogrow"=>true,
                "placeholder"=>"Use this to reply to the conversation.",
                "taClass"=>"form-control"
            ]);
            echo CHtml::submitButton("Send",[
                "class"=>"btn btn-info"
            ]);
            $this->endWidget();
        ?>
    </div>
</div>
