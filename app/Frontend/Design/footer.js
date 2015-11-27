module.exports = function makeFooter() {
    var fullHeight = 0
        + $("#MainPage").height()
        + $("footer").height();
    if(window.innerHeight < fullHeight) return $('#MainPage').css('margin-bottom',"auto");
    if($("body").height() > $("#MainPage").height()) {
        $('#MainPage').css('margin-bottom',(
            window.innerHeight
            - $('#MainPage').height()
            - $('footer').height()
        )+"px");
    } else {
        $('#MainPage').css('margin-bottom',"auto");
    }
}
