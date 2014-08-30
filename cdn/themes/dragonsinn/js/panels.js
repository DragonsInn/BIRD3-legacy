// Panel vars
var pLeft = $("#Pleft"),
    pRight = $("#Pright"),
    pTop = $("#Ptop");
// Panel triggers
var tLeft = $("#trigger-left"),
    tRight = $("#trigger-right"),
    tTop = $("#trigger-top");
if(useBottomPanel) {
    var pBottom = $("#Pbottom");
    var tBottom = $("#trigger-bottom");
}

tLeft.click(function(e) {
    e.stopPropagation();
    pLeft.toggleClass("panel-side-active");
    disableOthers("left");
});
tRight.click(function(e) {
    e.stopPropagation();
    pRight.toggleClass("panel-side-active");
    disableOthers("right");
});
tTop.click(function(e) {
    e.stopPropagation();
    pTop.toggleClass("panel-top-active");
    disableOthers("top");
});

// Now assign event stopper on bars.
$(pLeft).click(function(e){ e.stopPropagation(); });
$(pRight).click(function(e){ e.stopPropagation(); });
$(pTop).click(function(e){ e.stopPropagation(); });

if(useBottomPanel) {
    tBottom.click(function(e) {
        e.stopPropagation();
        pBottom.toggleClass("panel-bottom-active");
        disableOthers("bottom");
    });
    $(pBottom).click(function(e){ e.stopPropagation(); });
}

$(document).click(function(e) {
    pLeft.removeClass("panel-side-active");
    pRight.removeClass("panel-side-active");
    pTop.removeClass("panel-top-active");
    if(useBottomPanel) {
        tBottom.removeClass("panel-bottom-active");
    }
});

function disableOthers(panel) {
    if(panel != "left") pLeft.removeClass("panel-side-active");
    if(panel != "right") pRight.removeClass("panel-side-active");
    if(panel != "top") pTop.removeClass("panel-top-active");
    if(useBottomPanel) {
        if(panel != "bottom") tBottom.removeClass("panel-bottom-active");
    }
}
