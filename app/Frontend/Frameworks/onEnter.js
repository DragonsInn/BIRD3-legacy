import oo from "o.o";

// Tiny jQuery plugin. Rewritten for o.o
// http://stackoverflow.com/questions/6524288/jquery-event-for-user-pressing-enter-in-a-textbox
oo.publish({},{
    onEnter: function(fn) {
        return this.each(function() {
            this[0].addEventListener('enterPress', fn);
            this[0].onkeypress = function(e){
                if(e.keyCode == 13){
                    this[0].dispatchEvent(new Event("enterPress"));
                }
            };
        });
    }
});
