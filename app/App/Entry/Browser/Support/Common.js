// Bootstrap.Native
import "./BootstrapNative.js";

// Theme
import "BIRD3/Frontend/Design/Styles/main";
import "BIRD3/Frontend/Design/Styles/bs-extra";
import "BIRD3/Frontend/Design/footer";
import "BIRD3/Frontend/Design/panels";

// Icons
// import "BIRD3/Frontend/Design/Icons/Birdcons.main.font.js";

// o.o
import oo from "o.o";
import Visibility from "ally.js/src/dom/visible-quotient";

import Dialog from "BIRD3/Frontend/Frameworks/Dialog/Dialog";

// ES stuff
import pick from "BIRD3/Support/ES6Pickup";

// Publish to global space.
// That way, inline scripts can use it too.
window.oo = oo;

// Dialog
Dialog.installOn(oo);

// Visibility
oo.publish({},{
    visibility: function() {
        return Visibility(this[0]);
    },
    editor: function() {
        var self = this;
        require.ensure(["Editor/MarkdownEditor"], function(require){
            var editor_ = require("Editor/MarkdownEditor");
            var editor = pick(editor_);
            editor(self[0]);
        }, "BIRD3MarkdownEditor");
        return this;
    }
});

// BIRD3 Markdown editor
oo(function(){
    var findings = oo("body").find('div[data-b3me]').length;
    if(findings > 0) {
        oo("[data-b3me]").each((node) => {
            oo(node).editor();
        });
    }
});
