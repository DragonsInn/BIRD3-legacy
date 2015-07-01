<?php
    use HtmlObject\Image;
    use HtmlObject\Link;
?>
<h3>
    Conversation: <?=$convo->subject?>
    <small>Started by: <?=$convo->owner->username?></small>
</h3>

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
                'action'=>$this->createUrl("compose"),
                'enableAjaxValidation'=>false,
                'enableClientValidation'=>true,
                'htmlOptions'=>[
                    'name'=>'bird3-reply-to-msg',
                    #"class"=>"form-horizontal"
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
            echo CHtml::hiddenField("conv_id",$convo->id);
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
            <p class="media-heading">By: <?=$msg->sender->username?> @ time</p>
            <div><?=$msg->body?></div>
        </div>
    </div>
<?php endforeach; ?>
