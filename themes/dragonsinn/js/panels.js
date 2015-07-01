$.ready(function(){
    console.log("Setting up panels...");
    // Panel vars
    var useBottomPanel = window.useBottomPanel,
        pLeft = $("#Pleft"),
        pRight = $("#Pright"),
        pTop = $("#Ptop"),
        // Panel triggers
        tLeft = $("#trigger-left"),
        tRight = $("#trigger-right"),
        tTop = $("#trigger-top"),
        everything = $(".panel-pusher");

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

    // BIRD3 Menu stuff. Yup, its a panel too.
    mTabList = $("#BIRD3Menu > li.isTab");
    $("#BIRD3Menu").click(function(e){ e.stopPropagation(); });
    mTabList.click(function(e){
        var $e = $(e.target);
        var id = $e.closest("a").attr("href");
        var $li = $($e.closest("li.isTab"));
        $("#Ptop > div").hide();
        if(!$e.hasClass("active")) {
            // Scenario 1: Link is not active yet.
            if($("#Ptop").find(id).length > 0) {
                $($("#Ptop").find(id)[0]).show();
                $("#BIRD3Menu > li.isTab").removeClass("active");
                disableOthers("top");
                removePushers("top");
                pTop.addClass("panel-top-active");
                everything.addClass("panel-pusher-fromtop");
                $li.addClass("active");
            }
        }
        e.preventDefault();
    });

    // Finalize
    $("body").click(function(e) {
        console.trace("doc click");
        $("#BIRD3Menu > li.isTab").removeClass("active");
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
