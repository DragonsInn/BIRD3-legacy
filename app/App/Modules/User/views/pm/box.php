<h1>Conversations</h1>

<div class="well well-lg">
    <a class="btn btn-info" href="<?=$this->createUrl("/user/pm/compose")?>">
        Compose
    </a>
    <div class="vertical-divider"></div>
    <div class="btn-group" role="group" aria-label="Conversation filters">
      <button type="button" class="btn btn-default">All</button>
      <button type="button" class="btn btn-default pm pm-new">Unread</button>
      <button type="button" class="btn btn-default">By me</button>
      <button class="btn btn-default" role="button">To me</button>
    </div>
    <div class="vertical-divider"></div>
    <div class="btn-group" role="group" aria-label="Pages"><?php
        foreach($pages as $page) {
            echo CHtml::link(
                $page["label"],
                $page["url"],
                [
                    "class" => "btn btn-success"
                ]
            );
        }
    ?></div>
</div>

<ul class="list-group">
    <?php
        use HtmlObject\Image;
        use HtmlObject\Link;
    ?>
    <?php foreach($convos as $convo): ?>
    <li class="list-group-item pm pm-read">
        <div class="media">
            <div class="media-left">
                <?=Image::create(User::avatarUrl($convo->owner_id))
                    ->class("media-object")
                    ->alt("User avatar")?>
            </div>
            <div class="media-body">
                <div style="position:absolute;top:5px;right:5px;">
                    <?=CHtml::link("X",[
                        "leaveConvo","conv_id"=>$convo->id
                    ],[
                        "class"=>"btn btn-danger btn-xs"
                    ])?>
                </div>
                <h4 class="media-heading">
                    <?=Link::create(
                        $this->createUrl("/user/pm/show",[
                            "conv_id"=>$convo->id
                        ]
                    ))->value($convo->subject)?>
                </h4>
                <div>Started by: <?=$convo->owner->username?></div> <!-- <?=User::getHtml($convo->owner_id)?> -->
                <div>With: <?php
                    $mb = array();
                    foreach($convo->members as $member) {
                        $mb[] = $member->username;
                    }
                    echo implode(", ",$mb);
                ?></div>
                <div>Snippet from last message:
                    <div class="well"><?php
                        $msgs = $convo->messages;
                        $lm = end($msgs)->body;
                        $lm = nl2br($lm); # We wont parse this with Markdown.
                        if(strlen($lm) < 22)
                            echo $lm;
                        else
                            echo substr($lm, 0, 22)."[...]";
                    ?></div>
                </div>
            </div>
        </div>
    </li>
    <?php endforeach; ?>
</ul>
