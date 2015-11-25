<h3>Create a conversation</h3>
<p>
    You can create a converation with one OR many people. Separate the usernames by a comma in order
    to reply. send it to multiple people.
</p>

<!-- FIXME: Error summary -->

<div class="media">
    <div class="media-left">
        <?php /*Image::create(User::avatarUrl())
            ->class("media-object")
            ->alt("My avatar")
        */?>
    </div>
    <div class="media-body">
        <?php
            # Sneak-form!
            echo Form::open([
                'id'=>'bird3-create-convo',
                'name'=>'bird3-reply-to-msg',
                #"class"=>"form-horizontal",
                "url"=>"/user/pm/compose"
            ]);
            echo Form::text("to",$to,[
                "class"=>"form-control",
                "placeholder"=>"To..."
            ]);
            echo Form::text("subject", null, [
                "class"=>"form-control",
                "placeholder"=>"Subject..."
            ]);
            echo Widget::MarkdownEditor($msg->body,[
                "useWell"=>false,
                "name"=>"body",
                "autogrow"=>true,
                "placeholder"=>"Use this to reply to the conversation.",
                "taClass"=>"form-control"
            ]);
            echo Form::submit("Send",[
                "class"=>"btn btn-info"
            ]);
            echo Form::close();
        ?>
    </div>
</div>
