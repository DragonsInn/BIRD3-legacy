# BIRD3: Facts
- Users
    - Profile
        * Show characters: No
        * Show latest submissions: No
        * Button to send PM to that user: No
        * Shouts (aka. public comments): ?
        * Use tabbar to add links to Art, Music, Essays, Characters, Custom page: No
        * Custom page (html, markdown, css): No
    * Permissions: ?
    - Private Messages (Conversations)
        * Box: Somewhat.
        * Send: Yes
        * Receive: ?
        * Is read/unread: No
        * Leave convo: No
    * Send issue to staff: No
- Characters (CharaBase)
    * Create: No
    * Private/community only/public, adult/clean: No
    - Profile
        * Sort in tabs: No
        * Allow additional page w/ CSS: No
        * Profile frontpage theme / custom, sanitized HTML: No
    * Share: No
    - Relationships (MakotoExtension)
        * Create: No
        * Notification to recepient: No
        * Graph: No
    - Families and Clans
        * Assign character/s to a family or clan: No
        * List families and clans publicy: No
    * Backup: No
    - Character Images
        * Clean/Adult: No
    - Permissions
        * Delete: No
        * Change owner: No
        * Edit: No
        * Disable: No
    * Track usage: No
    * Status new, played, old: No
- Blog
    * Per-user: No
    * Frontpage: No
    * Permission to post to front: No
- Forum
    * Section, Board, Topic, Post: No
    * Move topics and boards as moderator or per permission: No
    * Board-specific mods: No
    * E-Mail notifications: No
    * Sticky topics in board: No
- Gallery (Pictures, Music, Essays)
    * Per-user gallery: No
    * Front-page widget (carousel) to show latest stuff: No
    * Folders: ?
    * Comments: No
    * Content-focused submission page: No
- Chat (Integrate AJAXChat)
    * Proxy into namespace `\BIRD3\App\Modules\Chat\AJAXChat`
    * Provide webpack-able JS
    * Use framework's view renderer: No
    * Users from Database: No
    * Places/hotel rooms as channels: No
    * CharaBase integration: No
    * Integrated uploader: No
    * HTML5 Audo using Howler.js: No
    * Smoothen animations (KUTE.js): No
    * Show room/place description inside chat: No
    * Rewrite chat template to use better tabs: No
    * Mobile-first interface: No
    * Hide tabs on smaller viewport: No
    * Give proper ARIA information: No
    * Use JSON instead of XML: No
    * Terminal mode: No
- Help center (docs)
    * SPA within MPA: use webpack to generate docs off markdown.
- Story
    * Public story: No
    - Jobs
        * Assign jobs to characters: No
        * Job assignment permission: No
        * Job listings: No
    - Places/Rooms
        * Sync markdown into DB to generate places: No
- Polls
    * Public, frontpage polls: No
    * Should users be able to post polls?
- Staffboard
    * Leave notes for other staff members: No
    * Share files permanently in staff: No
    - Issue tracking
        * Via the "send issue" button (db): No
        * Via email (using custom email server): No
    * Manage bans: No
    * Manage/sync places/rooms: No
- Search
    * Searches in Users, Galleries, Forum and Help: No
    * Execute command instead of search: No
- Terminal
    * `character`: No
    * `blog`: No
    * `php`: No
    * `js`: No
    * `stats`: No
- Troubbleshooting and Status
    * Status page: No
    * Tracking status via statsd and alike: No
    * Tracking process status: No
- Security
    * Ban system: Need to think of a good one.
    * Maintenance mode: Through framework

# BIRD3: Coding standpoints
- Backend
    * Use system installed PHP -gt 5.5.4
    * Code in ES6 or Uniter PHP
    * PHP backend is...in PHP.
- Frontend
    * Coding in OJ (ES6 support comming)
        - Maybe going to Uniter PHP

# BIRD3: Modules
- BIRD3 Markdown Editor
    * Make available on NPM: ?
    * Uses: OJ, PHP (Laravel Widget)
- PowerHouse: Open Sourced
- AJAXChat modifications: Fork, open source
- Birdcons
    * Commission Taala: not done yet
    * Currate list of icons and group them: Done
- WebPack loaders/plugins
    * fontgen-loader: Actively being used by all but myself. xD
    * PurifyCSS plugin: Open sourced.
- o.o
    * Write the o.o library: Not done yet
    * Should use: ES6
- Laravel stuff
    * Bootstrap.native integration?
    * Coded a view renderer: FlipFlop (`BIRD3\Extensions\Flipflop`). Tiny, fast, kewl.
- BIRD3 Web server
    * Find a way to externalize this beast. It rocks. People should be able ot have it.
    * Uses: JS, PHP, hprose
    * Name: WebDriver. `BIRD3\Foundation\WebDriver`
- BIRD3 Sounds for UI and chat (module has no name, should have one)
    * Poke jeje like crazy: not done yet.

# BIRD3: Interactiveness
- SocketCluster is used to communicate with the backend
    + Uses a pollyfill for WS unsupported browsers
- On main site
    * Notifications
- On chat
    * A SC channel should equal a chat channel
        - That actually might allow multi-channel stuff. o.o
    * Another SC channel is reserved for broadcasts
    * Whispers are sent thorugh the current channel too (`/msg $NAME $MESSAGE`)
    * Use a channel for terminal, once turned on
    * Find a good way to handle private rooms

# BIRD3: Text mode
- Allow SSH access using username and password
- Provide terminal commands and some others like chat
- Make the chat completely accessible through the terminal
    * Use "beep" as the per-message notification sound

# BIRD3: User Interface
- WAI-ARIA: Used for the most part
- Panels:
    - Left: Search
    - Right: User login/sidebar
- Colors:
    - Contrast colors: black, blue, purple, green, yellow, red
    - need to find specific colours
- Transparency
    - used on smaller on-site panels, like the background for thumbnails
    - Bootstrap Panels
- Blur
    - Only used on supported browsers
    - Background of the site is blurred to shift focus onto the content

- Ask thednp to help out at some point.
