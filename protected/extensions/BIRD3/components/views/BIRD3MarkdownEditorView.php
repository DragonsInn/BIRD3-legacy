<?php $ta = $form->textarea($model, $attr, [
    "class"=>"form-control",
    "style"=>"height:200px;margin-top:5px;margin-bottom:5px;",
    "data-autogrow"=>($this->autogrow ? "true" : "false")
]); ?>

<?php if($this->useWell): ?>
<div class="well well-sm" id="<?=$wid?>_editor">
<?php else: ?>
<div id="<?=$wid?>_editor">
<?php endif; ?>
    <?php if($this->editorPlacement == "top") echo $ta; ?>
    <div class="btn-toolbar" role="toolbar" aria-label="Formatting options">
        <div class="btn-group btn-group-<?=$this->groupSize?>" role="group" aria-label="Font settings">
            <button class="btn btn-default" data-func="bold" data-toggle="tooltip"
                    data-placement="<?=$this->placement?>" data-container="body" title="Bold">
                <b>B</b>
            </button>
            <button class="btn btn-default" data-func="italic" data-toggle="tooltip"
                    data-placement="<?=$this->placement?>" data-container="body" title="Italic">
                <i>I</i>
            </button>
            <button class="btn btn-default" data-func="font_color" data-toggle="tooltip"
                    data-placement="<?=$this->placement?>" data-container="body" title="Font color">
                <i class="glyphicon glyphicon-text-color" aria-hidden="true"></i>
                <span class="sr-only">Font color</span>
            </button>
            <button class="btn btn-default" data-func="bg_color" data-toggle="tooltip"
                    data-placement="<?=$this->placement?>" data-container="body" title="Background color">
                <i class="glyphicon glyphicon-text-background" aria-hidden="true"></i>
                <span class="sr-only">Background color</span>
            </button>
        </div>

        <?php if($this->textDisplay): ?>
        <div class="btn-group btn-group-<?=$this->groupSize?>" role="group" aria-label="Text display">
            <button class="btn btn-default" data-func="code" data-toggle="tooltip"
                    data-placement="<?=$this->placement?>" data-container="body" title="Code">
                <i class="fa fa-code" aria-hidden="true"></i>
                <span class="sr-only">Code</span>
            </button>
            <button class="btn btn-default" data-func="code_block" data-toggle="tooltip"
                    data-placement="<?=$this->placement?>" data-container="body" title="Code block">
                <i class="fa fa-file-code-o" aria-hidden="true"></i>
                <span class="sr-only">Code block</span>
            </button>
            <button class="btn btn-default" data-func="quote" data-toggle="tooltip"
                    data-placement="<?=$this->placement?>" data-container="body" title="Quote">
                <i class="fa fa-quote-right" aria-hidden="true"></i>
                <span class="sr-only">Quote</span>
            </button>
            <button class="btn btn-default" data-func="ol" data-toggle="tooltip"
                    data-placement="<?=$this->placement?>" data-container="body" title="Ordered list">
                <i class="fa fa-list-ol" aria-hidden="true"></i>
                <span class="sr-only">Ordered List</span>
            </button>
            <button class="btn btn-default" data-func="ul" data-toggle="tooltip"
                    data-placement="<?=$this->placement?>" data-container="body" title="Unordered list">
                <i class="fa fa-list-ul" aria-hidden="true"></i>
                <span class="sr-only">Unordered List</span>
            </button>
        </div>
        <?php endif; ?>

        <div class="btn-group btn-group-<?=$this->groupSize?>" role="group" aria-label="Links">
            <button class="btn btn-default" data-func="link" data-toggle="tooltip"
                    data-placement="<?=$this->placement?>" data-container="body" title="Link">
                <i class="glyphicon glyphicon-link" aria-hidden="true"></i>
                <span class="sr-only">Link</span>
            </button>
            <button class="btn btn-default" data-func="image" data-toggle="tooltip"
                    data-placement="<?=$this->placement?>" data-container="body" title="Image">
                <i class="fa fa-picture-o" aria-hidden="true"></i>
                <span class="sr-only">Image</span>
            </button>
        </div>
        <div class="btn-group btn-group-<?=$this->groupSize?>" role="group" aria-label="Options">
            <button class="btn btn-primary" id="<?=$wid?>_preview" data-toggle="tooltip"
                        data-placement="<?=$this->placement?>" data-container="body" title="Preview">
                <i class="fa fa-eye" aria-hidden="true"></i>
                <span class="sr-only">Preview</span>
            </button>
            <a class="btn btn-info" data-toggle="tooltip" data-placement="<?=$this->placement?>"
                    data-container="body" title="Help and Info" href="#">
                <i class="fa fa-question-circle" aria-hidden="true"></i>
                <span class="sr-only">Help and info</span>
            </a>
        </div>
    </div>
    <?php if($this->editorPlacement != "top") echo $ta; ?>
</div>
<script>$(document).ready(function(){
    $("#<?=$wid?>_preview").click(function(e){
        console.log("To: <?=$this->controller->createUrl("/tools/render_markdown")?>");
        e.preventDefault();
        $.post("<?=$this->controller->createUrl("/tools/render_markdown")?>", {
            md: $("#<?=$wid?>").val(),
        },function(data, status, xhr){
            $html = $(data);
            $html.filter("pre").find("code").each(function(i, block) {
                $(block).parent().css({"border":"none", "background":"none"});
                hljs.highlightBlock(block);
            });
            var $div = $("<div/>").html($html);
            BootstrapDialog.show({
                title: app.getTitle()+": Markdown preview",
                type: BootstrapDialog.TYPE_DEFAULT,
                size: BootstrapDialog.SIZE_WIDE,
                message: $div,
                buttons: [{
                    label: "OK",
                    action: function(d) { d.close(); }
                }],
                onshow: function(dialog) {
                    dialog.getModalDialog().addClass("modal-lg");
                },
                onhidden: function(dialog) {
                    $("#<?=$wid?>").focus();
                }
            });
        });
    });
    $("#<?=$wid?>_editor button").click(function(e){
        // These buttons should not do anything.
        e.preventDefault();
        e.stopPropagation();
        var o = $(this),
            ta = $("#<?=$wid?>"),
            func = o.data("func");
        if(typeof func != "undefined") {
            switch(func) {
                case "bold":
                    ta.surroundSelectedText("**","**","collapseToEnd");
                    ta.focus();
                break;
                case "italic":
                    ta.surroundSelectedText("_","_","collapseToEnd");
                    ta.focus();
                break;
                case "font_color":
                    var html_fg = "<p>Choose a font color.</p>"
                             + "<p>Type a color name or any valid CSS color description here.</p>"
                             + '<input type="text" class="form-control" name="color_val"/>';
                    BootstrapDialog.show({
                        title: app.getTitle(),
                        message: html_fg,
                        data: {self:o, input:ta},
                        buttons: [{
                            label: "Use this!",
                            action: function(dialog) {
                                var val = dialog.getModalBody().find('input[name=color_val]').val();
                                console.log("Val is: "+val);
                                dialog.getData("input").surroundSelectedText(
                                    '<font style="color:'+val+';">',
                                    '</font>',
                                    'collapseToEnd'
                                );
                                dialog.close();
                            }
                        }],
                        onhidden: function(dialog) {
                            dialog.getData("input").focus();
                        }
                    });
                break;
                case "bg_color":
                    var exurl = "http:\/\/example.com/image.jpg";
                    var html_bg = "<p>Choose a background color.</p>"
                             + "<p>You can chose to either use an image as an URL, or a color. Example:</p>"
                             + "<pre><code>url(\""+exurl+"\")</code></pre>"
                             + "<p>You can also just use a color. Examples include using names like "
                             + "<code>red</code> or <code>lime</code>. But also values such as "
                             + "<code>rgb(255,255,255)</code> are possible. You can read more in the help "
                             + "section about editing. Click the "
                             + '<i class="fa fa-question-circle" aria-hidden="true"></i> Help button '
                             + "for more information.</p>"
                             + '<input type="text" class="form-control" name="color_val"/>';
                    BootstrapDialog.show({
                        title: app.getTitle(),
                        message: html_bg,
                        data: {self:o, input:ta},
                        buttons: [{
                            label: "Use this!",
                            action: function(dialog) {
                                var val = dialog.getModalBody().find('input[name=color_val]').val();
                                console.log("Val is: "+val);
                                dialog.getData("input").surroundSelectedText(
                                    '<font style="background:'+val+';">',
                                    '</font>',
                                    'collapseToEnd'
                                );
                                dialog.close();
                            }
                        }],
                        onhidden: function(dialog) {
                            dialog.getData("input").focus();
                        }
                    });
                break;
                case "code":
                    ta.surroundSelectedText("`","`","collapseToEnd");
                    ta.focus();
                break;
                case "code_block":
                    var html_cb = "<p>Please type in the name of the programming language "
                                + "that you would like to format this code in.</p>"
                                + "<p>You can also leave it empty and just click the "
                                + "'Use this!' button.</p>"
                                + "<p>The language name should be entered all in lowercase and "
                                + "without spaces. Example: <code>php</code>, <code>javascript</code> or "
                                + "<code>c++</code>.</p>"
                                + '<input type="text" class="form-control" name="cl_val"/>';
                    BootstrapDialog.show({
                        title: app.getTitle(),
                        message: html_cb,
                        data: {self:o, input:ta},
                        buttons: [{
                            label: "Use this!",
                            action: function(dialog) {
                                var val = dialog.getModalBody().find('input[name=cl_val]').val();
                                dialog.getData("input").surroundSelectedText(
                                    '```'+val+"\n\n", "```", 'collapseToEnd'
                                );
                                dialog.close();
                            }
                        }],
                        onhidden: function(dialog) {
                            dialog.getData("input").focus();
                        }
                    });
                break;
                case "quote":
                    var sel = ta.getSelection();
                    if(sel.length == 0) {
                        // The user has not selected anything, insert some quote lines.
                        ta.insertText("\n> \n> \n> ", sel.end, "collapseToEnd");
                    } else {
                        // The user has selected text. properly quote it.
                        var lines = sel.text.split("\n");
                        for(var i=0; i<lines.length; i++) {
                            lines[i] = "> "+lines[i];
                        }
                        var out = lines.join("\n");
                        ta.replaceSelectedText(out, "collapseToEnd");
                    }
                    ta.focus();
                break;
                case "ol":
                    var sel = ta.getSelection();
                    if(sel.length == 0) {
                        ta.insertText("\n1. \n2. \n3. ", sel.end, "collapseToEnd");
                    } else {
                        // The user has selected text. properly quote it.
                        var lines = sel.text.split("\n");
                        for(var i=0; i<lines.length; i++) {
                            lines[i] = (i*1+1)+". "+lines[i];
                        }
                        var out = lines.join("\n");
                        ta.replaceSelectedText(out, "collapseToEnd");
                    }
                    ta.focus();
                break;
                case "ul":
                    var sel = ta.getSelection();
                    if(sel.length == 0) {
                        // The user has not selected anything, insert some quote lines.
                        ta.insertText("\n- \n- \n- ", sel.end, "collapseToEnd");
                    } else {
                        // The user has selected text. properly quote it.
                        var lines = sel.text.split("\n");
                        for(var i=0; i<lines.length; i++) {
                            lines[i] = "- "+lines[i];
                        }
                        var out = lines.join("\n");
                        ta.replaceSelectedText(out, "collapseToEnd");
                    }
                    ta.focus();
                break;
                case "link":
                    var html_link = '<label for="name_txt">Display name</label>'
                                  + '<input type="text" name="name_val" class="form-control" id="name_txt" '
                                  + 'placeholder="My cool link">'
                                  + '<label for="url_txt">URL</label>'
                                  + '<input type="text" name="url_val" class="form-control" id="url_txt" '
                                  + "placeholder=\"http(s):\/\/...\">";
                    BootstrapDialog.show({
                        title: app.getTitle(),
                        message: html_link,
                        data: {self:o, input:ta},
                        buttons: [{
                            label: "Use this!",
                            action: function(dialog) {
                                var url = dialog.getModalBody().find('input[name=url_val]').val();
                                var name = dialog.getModalBody().find('input[name=name_val]').val();
                                dialog.getData("input").replaceSelectedText(
                                    '['+name+']('+url+')',
                                    'collapseToEnd'
                                );
                                dialog.close();
                            }
                        }],
                        onhidden: function(dialog) {
                            dialog.getData("input").focus();
                        }
                    });
                break;
                case "image":
                    var html_imag = '<label for="name_txt">Image description</label>'
                                  + '<input type="text" name="name_val" class="form-control" id="name_txt" '
                                  + 'placeholder="A very nice image">'
                                  + '<label for="url_txt">URL</label>'
                                  + '<input type="text" name="url_val" class="form-control" id="url_txt" '
                                  + "placeholder=\"http(s):\/\/...\">";
                    BootstrapDialog.show({
                        title: app.getTitle(),
                        message: html_imag,
                        data: {self:o, input:ta},
                        buttons: [{
                            label: "Use this!",
                            action: function(dialog) {
                                var url = dialog.getModalBody().find('input[name=url_val]').val();
                                var name = dialog.getModalBody().find('input[name=name_val]').val();
                                dialog.getData("input").replaceSelectedText(
                                    '!['+name+']('+url+')',
                                    'collapseToEnd'
                                );
                                dialog.close();
                            }
                        }],
                        onhidden: function(dialog) {
                            dialog.getData("input").focus();
                        }
                    });
                break;
            }
        }
    });
});</script>
