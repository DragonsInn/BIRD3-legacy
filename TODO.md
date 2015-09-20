## ToDo
- [X] Add functionality to ban system
- [ ] User module
    * [X] Profile page
    * [X] Associate User to permissions and settings
    * [X] Registration
        * User must agree to ToS, provide valid email and acrivate.
        * Consider using OpenID?
            - Nope. Thats a thing for later.
        * [ ] Activation per E-Mail
        * [ ] Password Reset
        * [X] md5 -> bcrypt (through Password.(php|js))
        * [X] Automatically convert BIRD2 password.
    * [X] Settings panel
        * [X] Let user upload an avatar.
        * [X] Edit User, UserProfile and Usersettings.
    * [X] Using/Updating `last_visited` and `create_at`
        - Damn, a typo im not gonna get rid of. Should be `created_at`...
    * [X] Avatar support
        * [X] Cacheable avatars
    * [X] Correct User DB relationships
    * [X] Private Messaging
        * [X] Add models
        * [X] Implement PM logic
            - [X] Create conversation
            - [X] View conversation
            - [X] Reply to conversation
            - [X] Opt-out of conversation (= Delete when 0 members)
    * [X] E-Mail notifications
        * [X] NodeJS scheduler for notifications
        * [X] Plant notifications via Yii.
        * [X] Markdown support (Done via PHP.)
    * [X] Previleges
        * [X] User groups (Admin, Moderator, VIP, User, Banned)
        * [X] Handle special previleges via DB (Blog to front-page, ...)
        * [X] Developer mode should show extra stuff and error messages are detailed.
    * [X] Fill the User section in the Community menu.
    * [ ] Generate HTML to
        - Show avatar
            * Shows a generic one if `avvie_ext==null`
        - Send PM
        - View profile
        - See blog
        - See gallery
- [X] Front-end caching, part 2
    * [X] Optimize sent cache headers
        - Etag? Cache-control? Which one to use at what?
        - ETag on JS, Cache-Control + Expires on anything else.
- [X] `bird3-hprose`
    * [X] Implement basic structure
        - [X] Workerman
        - [X] Hprose
        - [X] Workerman + Hprose = `hprose-workerman`!
    * [X] Fine-tuned error handling
    * [X] PHP logs to parent
        - Probably gonna do it via Redis after all...
        - Using REDIS indeed. Workerman forbids STDOUT usage.
- [ ] In-Site docs
    * [ ] Proxy the Wiki into a /docs module
        * [ ] Map the URL
    * [ ] Hotel>Story and Hotel>Places need docs too.
    * [ ] Write a proper credits page.
    * [ ] Terms of Service
    * [X] Roleplaying book/guide (Mostly done by Rayth)
- [ ] Front-end refactor
    - [ ] Finalize Preprocessor component for OJ (OhSoJuicy)
- [ ] Chat
    * [ ] Authentification through `BIRD3User`
    * [ ] Move message passing to NodeJS/Socket.IO
        * Important: Leave old method working for fallback
    * [ ] Implement HTML5 audio
    * [ ] Add sound for whispers.
    * [ ] Commands:
        - `/kick <time> <user>`: Temporarily remove a user.
            - Throws the user out of the chat, makes it inaccessible for a while.
            - [ ] Implement Chat-specific temporary banning.
        - `/ban <user>`: Perma-ban a user. Ban-hammer his bum.
        - `/move <user> <from> <to>`: Force a user to a differe tchannel.
        - `/mute <user>`: Disable a user from talking untill...
            - `/unmute <user>`: ...is called or the user was unmuted from the menu.
                - [ ] Implement temporary muting. Maybe a user permission?
        - `/warn <message>`: Special message mods and admins can post. Warns a user.
            - [ ] Implement a global Warning system that complys the rules.
        - `/highlight <message>`: The posted message is highlighted. Does not work with `/me`.
        - `/char <cID>`: Change to a char real fast.
            - [ ] Render IC details from JSON object, not text codes.
        - `// <message>`: Prefix and shuffix the message with parantheses.
        - `/afk <reason>`: Set AFK due to reason.
        - `/status <status>`: Set status (such as "Gaming")
            - [ ] Teach the chat custom status handling.
        - `/ignore <user>`: Dont show messages from this user anymore.
        - [ ] Ignore by ID, not name.
    * [ ] Change message transmission to JSON, even in PHP.
    * [ ] Mrs. Drach (chatbot) can be aquired for login duration
        * [ ] Mrs. Drach is always IC. Always render this way.
    * [ ] Links in user profiles
        - User Profile
        - Send PM
    * [ ] Shift from BBCode to Markdown/Parsedown
    * [ ] Interface
        - [ ] Use bootstrap to make it responsive, mobile ready
        - [ ] Use panels to hide formatting in `#Pbottom`
    * [ ] Use desktop/on-site notifications
    * [ ] Implement @mentions
        - [ ] Mentions play a special sound AND highlight the message.
    * [ ] Temporary file upload from the side bar.
    * [ ] Yii integration
        * [ ] Re-Write the chat to use Yii's `CController::render()` instead.
        * [ ] Load JS/CSS using `CClientScript`
    * [ ] Make sure that Log-Viewer works.
        * [ ] Use NodeJS to display logs, query its cache.
    * [ ] Show people in other channels in the sidebar too.
    * [ ] Chat is aware of site updates and posts that to users before BIRD3 goes down.
- [ ] Characters
    * Todos will come when I made some.
- [ ] Media
    * [ ] Universal media viewer
        - [ ] Media is displayed depending on type.
        - [ ] Go as HTML5 as possible.
    * [ ] Community options
        - Fave
        - Rate
        - Comment
    * [ ] Give users a media gallery.
- [ ] Forum
    * Structure
        - Sections -> Boards -> Topics -> Posts
    * [ ] Post update to user when a topic they wrote in is updated.
- [ ] Hotel
    * [ ] Jobs
        - Make a listing of jobs that would fit the hotel.
- [ ] Misc features
    - [ ] #HashTag support.
        - Posting
        - Rendering
        - Searching

## Notes... Scribbles...
(23:04:14) Sapphy: (whispers) -Birth and Death Tab-
Birthday
Place of birth

Deathdate
Place of death
Cause of death

(23:10:31) Sapphy: (whispers) -Alignment Tab-
Spirit status
Spirit condition
Alignment
Sub-alignment
Spirit type

(23:14:32) Sapphy: (whispers) -Story Tab-
History
Likes
Dislikes
Additional Description

-Appearance Tab-
Height
Weight
Eye details
Hair details

-Adult Tab-
Position (Dom or Sub preference)
Preferences


-- Staff meeting
- user previleges database to maintain previleges [Done]
- Adult pictures on profiles are always collapsed by default to promote sfw
