## ToDo
- [ ] Add functionality to ban system
- [ ] User module
    * [X] Profile page
    * [X] Associate User to permissions and settings
    * [ ] Registration
    * [ ] Settings panel
    * [ ] List user by superuser status
    * [ ] Using/Updating `last_visited` and `joined`
    * [X] Avatar support
    * [X] Cacheable avatars
    * [X] Correct User DB relationships
    * [ ] Private Messaging
        * [ ] Add model
        * [ ] Implement PM logic
    * [ ] E-Mail notifications
        * [ ] NodeJS scheduler for notifications
        * [ ] Plant notifications via Yii.
- [X] Front-end cache
    * [X] Caching CSS/JS
    * [X] Adding OJ support
    * [X] Optimizing images
        * [X] Caching optimized images
    * [ ] Minify JS/CSS
    * [X] Minify output HTML
    * [X] WingStyle caches itself
    * [X] Assets all have a cache header.
- [X] API support
    * [X] API structure
    * [X] Make them work.
- [ ] Interaction
    * [X] Connecting to Socket.IO to talk to backend
    * [ ] Use Deliver.js to transmit files
    * [X] Verify that `connect-yii` does uploads
- [ ] In-Site docs
    * [ ] Proxy the Wiki into a /docs module
        * [ ] Map the URL
    * [ ] Hotel>Story and Hotel>Places need docs too.
    * [ ] Write a proper credits page.
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
- user previleges database to maintain previleges
- Adult pictures on profiles are always collapsed by default to promote sfw
- within the tos, create a link to a proper RP guidelines page to hopefuly improve sidewide RPing.

- Implement oj middleware
