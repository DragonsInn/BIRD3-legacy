<?php
    use HtmlObject\Image;
    use HtmlObject\Link;
?>
<h3>
    Conversation: <?=$convo->subject?>
    <small>Started by: <?=$convo->owner->username?></small>
</h3>

<?php if(!empty($errors)): ?>
    <p style="color:red;">Could not send your message:</p>
    <ul>
        <?php foreach($errors as $sect=>$msgs): ?>
            <?=$sect?>
            <ul>
                <?php foreach($msgs as $msg): ?>
                    <li><?=$msg?></li>
                <?php endforeach; ?>
            </ul>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

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
                'id'=>'bird3-reply-to-msg',
                'enableAjaxValidation'=>false,
                'enableClientValidation'=>true,
                'htmlOptions'=>[
                    'name'=>'bird3-reply-to-conv',
                ]
            ));
            $this->widget("BIRD3MarkdownEditor",[
                "context"=>$form,
                "model"=>$newMsg,
                "useWell"=>false,
                "attribute"=>"body",
                "autogrow"=>true,
                "placeholder"=>"Use this to reply to the conversation.",
                "taClass"=>"form-control"
            ]);
            $scm = Yii::app()->securityManager;
            echo CHtml::hiddenField("conv_id", $scm->hashData($convo->id));
            echo CHtml::submitButton("Send",[
                "class"=>"btn btn-info"
            ]);
            $this->endWidget();
        ?>
    </div>
</div>
<hr>
<?php foreach($messages as $msg): ?>
    <div class="media">
        <div class="media-left">
            <?=Image::create(User::avatarUrl($msg->from_id))
                ->class("media-object")
                ->alt("User avatar")
            ?>
        </div>
        <div class="media-body">
            <?php $del = "";
                if($msg->from_id === User::me()->id) {
                    $del = CHtml::link("X",[
                        "deleteMessage",
                        "message_id"=>$msg->id
                    ],[
                        "class"=>"btn btn-danger btn-xs"
                    ]);
                }
            ?>
            <p class="media-heading"><?=$del?> By: <?=$msg->sender->username?> @ time</p>
            <div><?=$msg->body?></div>
        </div>
    </div>
<?php endforeach; ?>
