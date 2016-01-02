import oo from "o.o";

oo(function(){
    console.log("Setting up panels...");
    // Panel vars
    var useBottomPanel = BIRD3.useBottomPanel,
        everything = oo(".panel-pusher"),
        // Panes
        _panels = {
            top: oo("#Ptop"),
            left: oo("#Pleft"),
            right: oo("#Pright")
        },
        // Trigger
        _triggers = {
            top: oo("#trigger-top"),
            left: oo("#trigger-left"),
            right: oo("#trigger-right")
        },
        // Which ones to use
        sides = ["top", "left", "right"];

    if(useBottomPanel) {
        _triggers.bottom = oo("#trigger-bottom");
        _panels.bottom = oo("#Pbottom");
        sides.push("bottom");
    }

    // Tiny callback
    function eachSide(cb) {
        for(var i in sides) cb(sides[i], i);
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
        trigger(side) != null && trigger(side).on("click",function(e){
            e.stopPropagation();
            e.preventDefault();
            disableAndRemovePushers(side);
            panel(side) != null && panel(side).toggleClass("active-pane");
            everything.toggleClass("from-"+side);
        });
    });

    // Finalize
    oo("#MainPage").on("click",function(e) {
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
