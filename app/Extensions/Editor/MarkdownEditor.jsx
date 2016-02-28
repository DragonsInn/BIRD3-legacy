// Editor
import LRTEditor from "LRTEditor/src/LRTEditor"; // using fork
import LRTE_Highlight from "LRTEditor/src/LRTEditor.HighlightPlugin";
import LRTE_Minimal from "LRTEditor/src/LRTEditor.MinimalPlugin";

import md from "md-highlight"; // using fork
import hljs from "highlight.js/lib/highlight";

// Text editor behaviours
// TODO: Re-introduce jsCaret to get insert-before and insert-after.
//import behave from "behave.js";
//import jsCaret from "legacy!jsCaret/jsCaret.js";

// Color picker
import {Piklor} from "piklor.js"
import ToolbarTemplate from "./Views/toolbar";

// Bootstrap.native
import Tooltip from "bootstrap.native/lib/tooltip-native";
import Popover from "bootstrap.native/lib/popover-native";

// CSS
import "./Style/editor.scss";
import "LRTEditor/src/style.css";
import "md-highlight/style.css";

// Data
import {colors as palette} from "./Resources/nes-colors.json";

// Set langs
hljs.registerLanguage('javascript', require('highlight.js/lib/languages/javascript'));
hljs.registerLanguage('js', require('highlight.js/lib/languages/javascript'));
hljs.registerLanguage('xml', require('highlight.js/lib/languages/xml'));
hljs.registerLanguage('php', require('highlight.js/lib/languages/php'));
hljs.registerLanguage('css', require('highlight.js/lib/languages/css'));

// The actual logic
export default function BIRD3MarkdownEditor(targetNode, cb){
    cb = cb || function(){};
    var $el = oo(targetNode);
    var id = targetNode.id;
    var name = $el.data("name");
    var taClass = $el.data("taClass");
    var textDisplay = $el.data("textDisplay");
    var groupSize = $el.data("groupSize");
    var placeholder = $el.data("placeholder");
    var placement = $el.data("placement");
    var editorPlacement = $el.data("editorPlacement");

    // The toolbar - either on top or bottom.
    var Toolbar = ToolbarTemplate({
        wid: id,
        placement: placement,
        textDisplay: textDisplay,
        groupSize: groupSize
    });

    // This will become the content-editable.
    var Editor = (<div
        id={id+"_editorView"}
        className={[taClass, "bird3-editor"].join(" ")}
        innerHTML={targetNode.innerHTML}
        placeholder={placeholder}
    />)[0];
    targetNode.innerHTML = "";

    // Create the textarea. It will hold the actual value.
    // Now i actually need data-binding. o.o
    var TextArea = (<textarea
        id={(id+"_input")}
        name={name}
        className="YouDontSeeMeArea"
    />);
    Editor.addEventListener("input", (e)=>{
        TextArea.val(md.unhighlight(Editor.textContent));
    });

    // Pop the components in
    if(editorPlacement == "top") $el.appendChild(Editor);
    $el.appendChild(Toolbar);
    if(editorPlacement == "bottom") $el.appendChild(Editor);
    $el.appendChild(TextArea);

    // Rendering the Popovers and Tooltips...
    oo("[data-toggle=tooltip]").each(function(item){
        var options = {}, $i = oo(item);
        options.animation = $i.data('animation');
        options.placement = $i.data('placement');
        options.duration = $i.data('duration');
        options.delay = $i.data('delay');
        new Tooltip(item,options);
    });
    oo("[data-toggle=popover]").each(function(item){
        var options = {}, $i = oo(item);
        options.trigger = $i.data('trigger');
        options.animation = $i.data('animation');
        options.duration = $i.data('duration');
        options.placement = $i.data('placement');
        options.dismiss = $i.data('dismiss');
        options.delay = $i.data('delay');
        new Popover(item,options);
    });

    LRTEditor.initialize(Editor, [
        LRTE_Highlight,
        LRTE_Minimal
    ], {
        highlightCallback: function(el) {
            var out, src = el.textContent;
            // md-highlight throws a nasty Error when you give it an empty string.
            // This is a workaround.
            if(src.length > 0) {
                //src = md.unhighlight(src);
                out = md.highlight(src);
                if(out.charAt(out.length-1) != "\n") {
                    out += "\n";
                }
                el.innerHTML = out;
            }

            // Code highlights
            var els = document.getElementsByClassName("md-bcode"); // use ooDOM's .find() instead!
            for(var i=0; i<els.length; i++) {
                (function(e){ // JS and scopes... I kid you not. FIXME: ES6 scopes.
                    if(e.children.length <= 0) {
                        // Not a highlightable thing. Skip.
                        return;
                    }
                    var c = e.children[0]
                    if(c.className == "nohighlight") return;
                    e.className += " hljs hljs-fix";
                    c.className += " hljs";
                    hljs.highlightBlock(c);
                })(els[i]);
            }
        },
        addLineWrapper: false
    });

    // Auto-resize? Sure.
    /*
    var $ldt = oo(ta_l.input).parent().parent();
    TextArea.data("origSize", $ldt.height());
    ta_l.input.addEventListener("keyup", function(e){
        var $e = oo(e.target);
        var $parent = $e.parent().parent();
        var lh = parseFloat($e.css("line-height"));
        var size = parseFloat(TextArea.data("origSize"));
        var lines = parseInt($e.val().split("\n").length);
        var inner = (lh*lines);
        if(inner > size) {
            // The text "went over the borders". Then lets go borderlandsing!
            $parent.css("height", (inner)+"px");
        } else {
            $parent.removeAttr("style");
        }
    });
    */

    var $preview_btn = $el.find("#"+id+"_preview");
    $preview_btn.click(function(e){
        e.preventDefault();
        /* Old preview method.
        $.post(BIRD3.baseUrl+"/tools/render_markdown", {
            md: TextArea.val(),
        },function(data, status, xhr){
            $html = oo(data);
            var $div = oo("<div/>").html($html);
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
                    TextArea.focus();
                }
            });
        });
        */
    });

    // Button setup
    // Little wrapper method for the caret
    function placeAround(c){
        caret.insertBefore(c).insertAfter(c);
    }
    /*
    oo("#"+id+"_toolbar button[data-func=font_color]").each(function(e){
        e._popover = new Popover(e, {
            trigger: "click",
            template: '<div class="popover" role="tooltip">'
                + '<div class="arrow"></div>'
                + '<div class="popover-content color-picker"></div>'
                + '</div>',
        });
        // Re-implementing Popover open and close to allow
        // the color picker to work as expected.
        e._popover.open = function() {
            if (this.popover === null) {
                this.createPopover();
                this.stylePopover();
                this.updatePopover();

                // Color picker
                var content = oo(this.popover).find(".popover-content")[0];
                e._picker = new piklor(content, palette);
                console.log(e._picker);
                e._picker.colorChosen(function(col){
                    console.log("Color: ",col);
                });
                e._picker.open();
            }
        }.bind(e._popover);
        e._popover.close = function() {
            if (this.popover && this.popover !== null && this.popover.classList.contains('in')) {
                e._picker.close();
                this.popover.classList.remove('in');
                this.removePopover();
            }
        }.bind(e._popover);
    });
    */
    oo("#"+id+"_toolbar button").each(function(button){
        // Transform them.
        button.type = "button";
        oo(button).click(function(e){
            //console.log(e.target);
            // These buttons should not do anything.
            e.preventDefault();
            e.stopPropagation();
            var o = oo(this),
                func = o.data("func");

            if(typeof func != "undefined") {
                (function(switcher){
                    // Do this AFTER switcher.
                    // Ugly but works, I know. :)
                    switcher();
                    ta_l.update();
                    ta_l.input.focus();
                })(function(){
                    switch(func) {
                        // Simple
                        case "bold":    return placeAround("**");
                        case "italic":  return placeAround("_");
                        case "code":    return placeAround("`");
                        // Complex
                        case "font_color":
                            // FIXME
                            console.log(e.target._popover);
                            break;
                        case "bg_color":
                            // FIXME
                            break;
                        case "code_block":
                            // FIXME
                            break;
                        case "ol":
                            // FIXME
                            break;
                        case "ul":
                            // FIXME
                            break;
                        case "link":
                            // FIXME
                            break;
                        case "image":
                            // FIXME
                            break;
                    }
                });
            }
        });
        /* Old on-click method.
        function(e){
            // These buttons should not do anything.
            e.preventDefault();
            e.stopPropagation();
            var o = oo(this),
                ta = TextArea,
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
                        var exurl = "http://example.com/image.jpg";
                        var html_bg = require("./views/editor/bg_color.ejs")({
                            url: exurl
                        });
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
                        var html_cb = require("./views/editor/code_block.ejs");
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
                        var html_link = require("./views/editor/link.ejs")();
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
                        var html_imag = require("./views/editor/image.ejs")();
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
        }
        */
    });
}
