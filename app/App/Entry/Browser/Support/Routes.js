var grapnel = require("grapnel");

/*
    Routes are used to load entries.

    Entries:

        *               Index.oj, main.scss
        /upload         Upload.oj, Ladda.scss
        /chat           Chat.oj, Chat.scss
        /character      CharaBase.oj, CharaBase.scss
        /staffboard     Staffboard.oj, Staffboard.scss
        /convo          Conversations.oj
        /user           User.oj


    Pages are mostly navigated using pushState, or #!-routes.
    If a hashbang route is requested, Express should re-write it.

---

    This router is meant for SPA apps.

    In our case, we will:
    - pick up the route.
    - Send a PJAX request to the server
    - Shove the contents into the body.

    If a browser does not support PushState:
    - Let the site behave like it normally would.
    - Each click triggers a new load.
    - When a page loads, run the proper route code, if any.
*/

import oo from "o.o";
import ControllerExecutor from "BIRD3/Support/ControllerExecutor";
import url from "BIRD3/Support/UrlHelper";

export default function Routes(app) {
    app.get("/docs", function(req, e, next){
        require.ensure([
            "BIRD3/App/Controllers/DocsController",
            "BIRD3.docs"
        ], function(require){
            var controller = require("BIRD3/App/Controllers/DocsController");
            var docs = require("BIRD3.docs");
            (new Controller(docs)).render();
        }, "DocsController");
    });

    app.get("/chat", function(req){
        /*
            - Modernize the chat to work with WebPack
            - Load the chat
            - When the user leaves the page (aka. navigates away),
              log the user out using chat.logout();
            - chat.logout() should accept a callback, so that we can delay.
        */
        /*require(["BIRD3/App/Modules/Chat"], function(chat){
            // Do something with chat.
        });*/
    });

    // The main controller.
    app.get("/*", function(req, e, next){
        console.log("Handling main request");
        require.ensure([
            "BIRD3/App/Controllers/MainController.oj"
        ], function(require){
            var controller = require("BIRD3/App/Controllers/MainController.oj");
            ControllerExecutor(controller, app, req, e, next);
        }, "MainController");
    });
}
