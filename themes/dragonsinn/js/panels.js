$.ready(function(){
    console.log("Setting up panels...");
    // Panel vars
    var useBottomPanel = BIRD3.useBottomPanel,
        everything = $(".panel-pusher"),
        // Panes
        _panels = {
            top: $("#Ptop"),
            left: $("#Pleft"),
            right: $("#Pright")
        },
        // Trigger
        _triggers = {
            top: $("#trigger-top"),
            left: $("#trigger-left"),
            right: $("#trigger-right")
        },
        // Which ones to use
        sides = ["top", "left", "right"];

    if(useBottomPanel) {
        _triggers.bottom = $("#trigger-bottom");
        _panels.bottom = $("#Pbottom");
        sides.push("bottom");
    }

    // Tiny callback
    function eachSide(cb) {
        for(var i in sides) {
            cb(sides[i], i);
        }
    }
    function trigger(side) {
        if(_triggers[side][0] != null)
            return _triggers[side];
        else
            return null;
    }
    function panel(side) {
        if(_panels[side][0] != null)
            return _panels[side];
        else
            return null;
    }


    eachSide(function(side, i){
        console.log(side, trigger(side))
        trigger(side) != null && trigger(side).on("click",function(e){
            console.log("o.o! "+side);
            e.stopPropagation();
            e.preventDefault();
            disableAndRemovePushers(side);
            panel(side) != null && panel(side).toggleClass("active-pane");
            everything.toggleClass("from-"+side);
        });
    });

    // Finalize
    $("body").on("click",function(e) {
        disableAndRemovePushers(null);
    });

    function disableAndRemovePushers(pane) {
        eachSide(function(side){
            if(pane != side) {
                everything.removeClass("from-"+side);
                panel(side) != null && panel(side).removeClass("active-pane");
            }
        });
    }
});
