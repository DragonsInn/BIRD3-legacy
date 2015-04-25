// Tiny jQuery plugin
// http://stackoverflow.com/questions/6524288/jquery-event-for-user-pressing-enter-in-a-textbox
$.fn.pressEnter = function(fn) {
    return this.each(function() {
        $(this).bind('enterPress', fn);
        $(this).keyup(function(e){
            if(e.keyCode == 13){
                $(this).trigger("enterPress");
            }
        })
    });
};
