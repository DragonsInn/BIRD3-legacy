// Commons
import "./Support/Common";

// Modules
import Grapnel from "grapnel";
import oo from "o.o";
import Compatibility from "BIRD3/Support/Compatibility";
import Routes from "./Support/Routes";

var pushRouter = new Grapnel({
    pushState: true
});
var hashRouter = new Grapnel({
    pushState: false,
    hashbang: true
});

// Hacky way to enable hashbang AND pushState routing.
var app = pushRouter;
app.get = function(){
    hashRouter.get.apply(hashRouter, arguments);
    Grapnel.prototype.get.apply(this, arguments);
}

oo("[data-pjax]").click(function(e){
    if(hasPushState) {
        // This is a PJAX link. So hold it right there.
        e.preventDefault();
        app.navigate(oo(e.target).attr("href"));
    }
});

Compatibility(function(err){
    if(err) { console.log(err); }
    console.log("Loading routes...");
    Routes(app);

    app.on("navigate",function(){
        console.log(arguments);
        console.log("Navigating...");
    })

    //app.navigate();
    window.app = app;
});
