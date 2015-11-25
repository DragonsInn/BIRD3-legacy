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
*/
