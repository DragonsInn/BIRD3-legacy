# BIRD3 file structure

BIRD3/
    | php_modules_ext/
        - This is an extension to the php_modules folder installed via Composer.
    | web_modules/
        - Holds various packages not listed in a package manager.
    | php-lib/
        - The PHP backend.
    | node-lib/
        - The NodeJS backend.
    | web-lib/
        - The front-end contents.
        * Needs re-structure. Example below.
        | main.oj # The application runner for the usual pages.
        | chat.oj # the application runner, extended with chat usage.
        | compatibility.js # Install polifills and alike if needed
        | ext/
            | bootstrapper.scss # Bootstrap SASS binding. Implements accessibility plugin.
            | bootstrapper.js # JS part of bootstrap. Uses bootstrap.native!
    | oj-lib/
        - Holds the OJ frameworks and components, like `o.o`.
    | themes/
        - Holds the theme information for the system
    | config/
        - Holds the top-level configuration
    | cache/
        - Holds data:
            - Cached by PurifyHTML
            - Produced by WebPack. Has the current chunkhash inside.
            - Logs
            - Refactored images in cdn/ folder.
    | util/
        - Various utilities used for maintenance and NPM scripts.
    | protected/
        - The main Yii app. The folder might become renamed to app/.
    | cdn/
        - Holds all the static and public assets.
        | app/
            - WebPack output
        | content/
            - User-produced files.
            | submissions/
            | avatars/
        | sounds/
            - The sounds used in the chat.
