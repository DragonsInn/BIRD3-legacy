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

    // fire the global event, that BIRD3's frontent is ready.
    // http://stackoverflow.com/questions/2490825/how-to-trigger-event-in-javascript
    var event, evName = "BIRD3.ready";
    if (document.createEvent) {
        event = document.createEvent("HTMLEvents");
        event.initEvent(evName, true, true);
    } else {
        event = document.createEventObject();
        event.eventType = evName;
    }
    event.eventName = evName;
    if (document.createEvent) {
        window.dispatchEvent(event);
    } else {
        window.fireEvent("on" + event.eventType, event);
    }
});
