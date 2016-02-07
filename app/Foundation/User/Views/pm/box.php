<?php
    // Capture the current user.
    $user = Auth::user();

    // Helper
    $unreadCount = function($convo) use($user) {
        $count = 0;
        foreach($convo->messages as $message) {
            if(!$user->hasReadMessage($message)) {
                $count++;
            }
        }
        return $count;
    };
    $displayStrings = function($count) use($user) {
        if($count > 0) {
            // Unread messages found.
            $display = "success";
            $unreadStr = "($count)";
        } else {
            // All read.
            $display = "default";
            $unreadStr = "";
        }
        return ((object)[
            "display"   => $display,
            "unreadStr" => $unreadStr
        ]);
    };

    // Filter.
    $memberConvos = $convos->filter(function($v) use($user) {
        return $v->owner->id != $user->id;
    })->all();
    $myConvos = $convos->filter(function($v) use($user) {
        return $v->owner->id == $user->id;
    })->all();
?>
<div class="page-header">
    <h3>Your conversations</h2>
</div>

<ul class="nav nav-pills" role="tablist">
  <li role="presentation" class="active">
      <a href="#memberOf" id="memberOf-tab" aria-controls="memberOf" role="tab" data-toggle="tab">
          Member in
      </a>
  </li>
  <li role="presentation">
      <a href="#startedByMe" id="startedByMe-tab" aria-controls="startedByMe" role="tab" data-toggle="tab">
          Started by me
      </a>
  </li>
</ul>

<div class="row">
    <div class="col-md-4">
        <div class="tab-content">
            <!-- FIXME: Turn these into sub-views instead of this copy-pastery. -->
            <div role="tabpanel" class="tab-pane fade active in" id="memberOf" aria-labeledby="memberOf-tab">
                <?php foreach($memberConvos as $convo): ?>
                    <?php
                        $count = $unreadCount($convo);
                        $s = $displayStrings($count);
                    ?>
                    <div class="panel panel-<?=$s->display?>">
                        <div class="panel-heading">
                            <h3 class="panel-title" data-conv-id="<?=$convo->id?>">
                                <?=trim("{$convo->subject} {$s->unreadStr}")?>
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div>By: <?=$convo->owner->username?></div>
                            <div>With: <?php
                                $with = [];
                                foreach($convo->members as $member) {
                                    $with[] = $member->username;
                                }
                                echo join(", ", $with);
                            ?></div>
                            <div>Since: <?=$convo->created_at?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="startedByMe" aria-labeledby="startedByMe-tab">
                <?php foreach($myConvos as $convo): ?>
                    <?php
                        $count = $unreadCount($convo);
                        $s = $displayStrings($count);
                    ?>
                    <div class="panel panel-<?=$s->display?>">
                        <div class="panel-heading">
                            <h3 class="panel-title" data-conv-id="<?=$convo->id?>">
                                <?=trim("{$convo->subject} {$s->unreadStr}")?>
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div>By: <?=$convo->owner->username?></div>
                            <div>With: <?php
                                $with = [];
                                foreach($convo->members as $member) {
                                    $with[] = $member->username;
                                }
                                echo join(", ", $with);
                            ?></div>
                            <div>Since: <?=$convo->created_at?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="col-md-8" id="MessageContainer"></div>
</div>

<script class="es6">
    // FIXME: We probably should attempt to move all the inline stuff into controllers. O.o
    BIRD3.ready(() => {
        oo("[data-conv-id]").on("click", (e) => {
            e.preventDefault();
            let id = oo(e.target).data("convId");
            let url = BIRD3.baseUrl + "/user/pm/convo/" + id;
            oo.uxhr(url, {}, {
                method: "GET",
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                },
                complete: (res, code) => {
                    try {
                        let data = JSON.parse(res);
                        // Informative...
                        let pre = (<pre innerHTML={res}></pre>);
                        pre.appendTo(document.body);

                        // But actually, attach some.
                        const container = oo("#MessageContainer");
                        container.html("");
                        for(let i in data.messages) {
                            let message = data.messages[i];
                            let data = { id: message.id }; // FIXME: Data in o.o is broken.
                            container.appendChild(
                                <div className="well">
                                    <div>From: <span innerHTML={message.from}/></div>
                                    <div innerHTML={message.body}/>
                                </div>
                            );
                        }
                        container.appendChild(<hr/>);
                        let tagId = `reply-conv-${id}`;
                        let editorDiv = (<div
                            className="well"
                            data-conv-id={id}
                            data-name={tagId}
                            data-placement="bottom"
                            data-ta-class=""
                            data-autogrow="true"
                            data-text-display="true"
                            data-use-well="true"
                            data-editor-placement="bottom"
                            data-group-size="sm"
                            data-placeholder="Type here to reply"
                            data-height="200px"
                            data-id={tagId}
                        />);
                        let sendButton = (
                            <button className="btn btn-sm btn-success">
                                Reply now
                            </button>
                        );
                        sendButton.on("click", (e)=>{
                            oo.uxhr(url,{
                                pmReply: {
                                    conv_id: id,
                                    body: oo(`[name="reply-conv-${id}"]`).val()
                                }
                            },{
                                method: "POST"
                            });
                        });
                        container.appendChild(editorDiv);
                        container.appendChild(sendButton);
                        editorDiv.editor();
                    } catch(e) {
                        console.error(e)
                    }
                }
            });
        });
    });
</script>
