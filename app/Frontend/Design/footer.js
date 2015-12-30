import oo from "o.o";
export default function makeFooter() {
    var fullHeight = 0
        + oo("#MainPage").height()
        + oo("footer").height();
    if(window.innerHeight < fullHeight) return oo('#MainPage').css('margin-bottom',"auto");
    if(oo("body").height() > oo("#MainPage").height()) {
        oo('#MainPage').css('margin-bottom',(
            window.innerHeight
            - oo('#MainPage').height()
            - oo('footer').height()
        )+"px");
    } else {
        oo('#MainPage').css('margin-bottom',"auto");
    }
}
window.addEventListener("resize", makeFooter);
