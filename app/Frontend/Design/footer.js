import oo from "o.o";

export default function makeFooter() {
    var fullHeight = 0
        + oo("#MainPage").height()
        + oo("footer").height();
    if(window.innerHeight < fullHeight) return oo('#MainPage').css('margin-bottom',"auto");
    if(oo("body").height() > oo("#MainPage").height()) {
        oo("footer").css({
            position: "absolute",
            bottom: 0,
            left: 0,
            right: 0
        });
    } else {
        oo("footer").css({
            position: "relative"
        });
    }
}

// React on resize
window.addEventListener("resize", makeFooter);

// on DOM ready...
oo(makeFooter);
