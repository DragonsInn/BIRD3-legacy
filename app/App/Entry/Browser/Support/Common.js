// Bootstrap.Native
import "./BootstrapNative.js";

// Theme
import "BIRD3/Frontend/Design/Styles/main";
import "BIRD3/Frontend/Design/Styles/bs-extra";
import "BIRD3/Frontend/Design/footer";

// Icons
// import "BIRD3/Frontend/Design/Icons/Birdcons.main.font.js";

// o.o
import oo from "o.o";
import Visibility from "ally.js/src/dom/visible-quotient";

// Publish to global space.
// That way, inline scripts can use it too.
window.oo = oo;

// Visibility
oo.publish({},{
    visibility: function() {
        return Visibility(this[0]);
    }
});

// BIRD3 Markdown editor
oo(function(){
    var findings = oo("body").find('div[data-b3me]').length;
    if(findings > 0) {
        require.ensure(["Editor/MarkdownEditor"], function(require){
            // Easy.
            var editor_ = require("Editor/MarkdownEditor");
            var editor = editor_.default || editor_;
            oo("[data-b3me]").each((node) => {
                editor_(node);
            });
        }, "BIRD3MarkdownEditor");
    }
});
