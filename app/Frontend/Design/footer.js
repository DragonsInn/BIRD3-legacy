import oo from "o.o";

export default function makeFooter() {
    var fullHeight = 0
        + oo("#MainPage").height()
        + oo("footer").height();
    if(window.innerHeight < fullHeight) {
        oo('#MainPage').css('margin-bottom',"auto");
    } else {
        oo('#MainPage').css('margin-bottom',"0px");
    }
    if(oo("body").height() > fullHeight) {
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

// When we press Enter (keyCode : 13),
// then we might have enlarged the site.
window.addEventListener("keyup", (e)=>{
    if(e.keyCode == 13) makeFooter();
});

// Likely not very good performance-wise :/ But usable.
window.addEventListener("DOMSubtreeModified", makeFooter);

// on DOM ready...
oo(makeFooter);
