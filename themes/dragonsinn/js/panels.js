$(function(){
    // Panel vars
    var pLeft = $("#Pleft"),
        pRight = $("#Pright"),
        pTop = $("#Ptop");
    // Panel triggers
    var tLeft = $("#trigger-left"),
        tRight = $("#trigger-right"),
        tTop = $("#trigger-top");
    var everything = $(".panel-pusher");
    if(useBottomPanel) {
        var pBottom = $("#Pbottom");
        var tBottom = $("#trigger-bottom");
    }

    tLeft.click(function(e) {
        e.stopPropagation();
        removePushers("left");
        disableOthers("left");
        pLeft.toggleClass("panel-side-active");
        everything.toggleClass("panel-pusher-toright");
    });
    tRight.click(function(e) {
        e.stopPropagation();
        disableOthers("right");
        removePushers("right");
        pRight.toggleClass("panel-side-active");
        everything.toggleClass("panel-pusher-toleft");
    });
    tTop.click(function(e) {
        e.stopPropagation();
        disableOthers("top");
        removePushers("top");
        pTop.toggleClass("panel-top-active");
        everything.toggleClass("panel-pusher-fromtop");
    });

    // Now assign event stopper on bars.
    $(pLeft).click(function(e){ e.stopPropagation(); });
    $(pRight).click(function(e){ e.stopPropagation(); });
    $(pTop).click(function(e){ e.stopPropagation(); });

    if(useBottomPanel) {
        tBottom.click(function(e) {
            e.stopPropagation();
            disableOthers("bottom");
            removePushers("bottom");
            pBottom.toggleClass("panel-bottom-active");
        });
        $(pBottom).click(function(e){ e.stopPropagation(); });
    }

    $(document).click(function(e) {
        pLeft.removeClass("panel-side-active");
        pRight.removeClass("panel-side-active");
        pTop.removeClass("panel-top-active");
        everything.removeClass("panel-pusher-fromtop");
        everything.removeClass("panel-pusher-toleft");
        everything.removeClass("panel-pusher-toright");
        if(useBottomPanel) {
            tBottom.removeClass("panel-bottom-active");
            everything.removeClass("panel-pusher-frombottom");
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

    function removePushers(panel) {
        if(panel != "left") everything.removeClass("panel-pusher-toright");
        if(panel != "right") everything.removeClass("panel-pusher-toleft");
        if(panel != "top") everything.removeClass("panel-pusher-fromtop");
        if(useBottomPanel) {
            if(panel != "bottom") everything.removeClass("panel-pusher-frombottom");
        }
    }
});
