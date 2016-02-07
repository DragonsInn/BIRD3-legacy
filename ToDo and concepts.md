# ToDos
The following document contains ToDos ("To Be Done" notes), categorized by module/feature, organized into headings of at least the second level.

# List
## Module: Core (BIRD3 Foundation)
- [ ] Provide a style-guide for the code.
- [ ] Introduce code linting and style checking.
- Tests
    - [X] Browser
    - [X] Server: NodeJS
    - [X] Server: PHP
- [ ] Improve test coverage.
- [ ] Introduce code coverage for all the languages.
- [ ] Define rules by which files are written in which format.
- [ ] Use PJAX for navigation where possible.
    - Rewrite all "internal" links to be of signature `<a data-pajax href="...">`
    - All `a[data-pjax]` links result in a PJAX load. All others, don't.
    - Consider actually using `target="this"` instead. Looks cleaner, more HTML compliant.

## Module: Help (Docs)
- [ ] Synchronize Wiki and local docs through Git submodule
- [ ] Editing the docs in the page also should do something gitty.

## Module: Security
- [ ] Middlewares to prevent common anti-patterns.

## Feature: Dragon's Inn Flavoured Markdown (DIFM)
- [ ] Use [Parser](/asmblah/parsing) to introduce Markdown to BIRD3.
    - Syntax:
        * `_foo_` / `*foo*`: Italic (inline)
        * `__foo__` / `**foo**`: Bold (inline)
        * `--foo--`: Strikethrough (inline)
        * `<c:$VALUE>foo</c>`: Color for foreground. (inline, multi-line)
        * `<bg:$VALUE>foo</bg>`: Background color. (inline, multi-line)
        * ` ```language\nvar foo=42;\n``` `: Code fence, where: Fence, language, newline, code, fence. (Block)
        * `> foo` (more `>` = deeper level): Quote (Single line)
        * `- foo` / `* foo`: Bulletin line (single line)
        * `(foo)[$urlSpec]`: URL (inline)
        * `@$name` (`$name` => `[0-9a-zA-Z_-\.,!\?'"]`): Mention (inline)
        * `#$hashtag` (`$hashtag` => `[0-9a-zA-Z!\?]`): Hashtag (inline)
        * `!($alt)[$urlSpec]`: Image (inline)
    * Allow raw HTML, but sanitize away these tags:
        * `<script>`: Avoid malicious scripts.
        * `<link>`: Avoid external CSS, that could actually include JS too.
        * `<embed>`: Embedded objects might introduce malicious code.
        * `<iframe>`: Has access to parent frame, would allow XSS.
        * `<object>`: Not only is Flash deprecated, but it is able to do stuff unseen by the user.

## Module: User
- [ ] Registration
    - Should we allow OpenID registrations? OpenID: Twitter, Facebook, SoFurry. Would possibly skip verification.
    - [ ] Must accept ToS (Terms of Service) (To be written)
    - [ ] Activation is performed via EMail
        - [ ] Use an email HTML template. Laravel can probably make one...
- [ ] Password reset
    - User will get an email with a token that expires within time to reset his password.
    - User will have to know some details about his profile, otherwise prohibit the reset in case of an exploit.
- [ ] Login
    - [ ] Persistent login via JWT.
- [X] Convert BIRD2 style MD5 passwords to AES (already done.)
- [ ] Update `tbl_users.last_visited` to a timestamp of last visit.
    - [ ] Currently, the column is `last_visit`. Need to rename that.
    - [ ] Use SocketCluster to update once the client has connected (use the `connect` event or a middleware.)
- [ ] Widget to display when hovering over a user's link
    - Should include:
        - Avatar image
        - Display name
        - User name
        - Links to profile and sending a message

### Messaging
A conversation is a private thread, shared by 2 or more users, where one is the author, and the others are participants.

- [ ] View conversations
    - [ ] Differentiate between created and joined conversations
    - [ ] Reply inline
    - [ ] Get full conversation view page
    - [ ] Paginate between N conversations
- [ ] Compose conversation
- [ ] Leave conversation

### Permissions
A user may do, or not do something.

- [ ] Integrate Entrust: https://github.com/Zizaco/entrust
    - [ ] Create migration based on Phinx instead of Laravel.
    - [ ] Create a Seed based on Phinx. Should seed: Owner, Admin, Moderator, VIP, User.
- [ ] Make the internal `BIRD3\Foundation\User\Entity` aware of it's external role.

### Banning / kicking
A user might be banned or kicked for a period of time.

- [ ] Kicks are always time based and can never belonger than a day.
- [ ] Bans can be infinite OR time based. They are infinite by default and have no caps.
    - When banning a user, a lot of their content is stripped off the site.
    - The user is sent a backup of his stuff via email.
    - Banned accounts are cleared away on a time basis.

### Profile
The prettiest page of them all. A collection of the user's info, data, media and things.

- [ ] Fields:
    * Facebook (API)
    * Twitter (API)
    * SoFurry (API)
    * FurAffinity
    * FurryNetwork (is in Beta)
    * FurNation (Do they even have an API?)
    * Weasyl (API?)
    * Furry Within (SMF based, no API)
    * PlayStation Network (API?)
    * XBox Live (API?)
    * Skype (API?)
    * Github (API. For all dem devs :3)
    * Personal website
- [ ] Allow additional fields to be added.
- [ ] Use widgets to present:
    - Artwork
    - Characters
    - Music
    - Stories
- [ ] Shoutbox
- [ ] Allow a tiny custom page.
    - Write with markdown
    - prettify with CSS

### Settings
Where the user controls his stuff.

- [ ] Avatar upload
    - 500x500, jpeg, png, gif
- [X] Enable or disable NSFW content. Off by default.

### Blog
A blog each user has.

- [ ] Create
- [ ] Edit
- [ ] Delete
- [ ] Comment

### Interaction
User A and User B can interact with one another.

- [ ] Follower system
    - Followers see updates of newest submissions or blog posts.
    - Followers should be able to opt-out of specific things to follow.
- [ ] Favorite system
    - Users can favorite content
    - Should users be able to rate it, too?

### Permissions list
The things a user can, or can not do, as a table.

Short name              | Name                       | Description
------------------------|----------------------------|------------
messaging:compose       | Creating a message         | With this, the user is allowed to compose a message.
messaging:reply         | Replying to a message      | With this, the user can reply to messages.
character:create        | Creating a character       | With this, the user can create a character.
character:upload-image  | Uploading character images | With this, the user is permitted to upload character images. Rewokred on abuse.
character:init-relation | Relating a character       | With this, a user is allowed to relate his/her character to another.
media:upload            | General media upload       | With this, the user can upload music, stories or artwork.
media:upload-music      | Music upload               | Can upload music.
media:upload-story      | Story upload               | Can upload story.
media:upload-art        | Artwork upload             | Can upload art.
comment:make            | Creating a comment         | With this, a user may create a comment. Can be revoked if massive harrassment or alike seems to be all the user can talk about. Also applies for replying to comments.
blog:write              | Writing a blog             | Can this user write to his blog? If not, then the blog isn't shown if it has no records.
blog:write-public       | Writing public entries     | With this, a user can write a public blog entry. That means, one that is shown on the front page. Blog posts by users with this permission are collected and become the frontpage blog.
forum:write             | Write to forum             | Can this user write an entry in a forum?
forum:make-topic        | Forum topic creation       | Can this user create a forum topic? Independent of the board to be posted in.
chat:enter              | Log into the chat          | Can this user enter the chat?
chat:post               | Post to the chat           | Synchron with `/mute` command. Without this perm, or it being disabled, the user can't write to the chat.
issue:report            | Issue reporting            | Can this user report issues? It may be revoked for abusive usage.
place:create            | Creating a place           | With this, a user is permitted to create a place.
place:edit              | Editing a place            | Editing the description and details of a place.
place:delete            | Deleting a place           | Deleting of a place.
job:create              | Creating a job             | Create a job to/for ane xisting place.
job:edit                | Editing a job              | Editing a job.
job:move-place          | Moving a job to place      | With this, a user can also change the place this job belongs to.
job:delete              | Delete job                 | With this, a user can delete a job.

## Module: Staff (Staffboard, OS.js)
The staff has powers. These features define some of them, plus initially define some permissions. More of them below.

- [ ] Programmatically register a new user
- [ ] Delete users
- [ ] Create elements in the story (Places, Jobs)
- [ ] Change character ownership (`Character::find($id)->changeOwner($newId)`)
- [ ] Change visibility level of content
- [ ] Invoke or revoke permissions on a user
- [ ] Edit descriptive content
- [ ] Write current descriptive content down as a seed dependency and initiate a Pull Request (VERY ADVANCED)
    - Requires Gitlab API
- [ ] Share notes
- [ ] Share files
- [ ] Provide means to deal with issues
    - Allow gitlab/github interaction
    - Show internal email (configured. Example: "issues@dragonsinn.tk")
- [ ] Manage bans and kicks

## Feature: Issues
Provides a button at the bottom of the page to report issues. And also an email.

- [ ] Only accept email whose subject contains a string.
    - Example: "[DragonsInn] "
- [ ] Redirect users to Github or Gitlab if they have a code-related thing.
- [ ] Provide a status page

## Module: Media
Music, Artwork, Stories

- [ ] User has to agree to the Terms of uploaded content (TUC)
    - TUC to be written.
- [ ] Have the user enter at least N tags
    - N: 2, possibly more or less. To be talked through with staff.
- [ ] Comments
    - [ ] Reply to comments
- [ ] Sort submissions in folders

## Module: Characters
Possibly the most important thing that makes the Dragon's Inn the Dragon's Inn that it is.

- [ ] Create
- [ ] Edit/Update
- [ ] View page
- [ ] Delete
- [ ] Share a character with other user/s
- [ ] Short URLs for characters (`/char/{nickname, urlname or cID}`)
    - `urlname`: Based on the nickname, but if it already exists, shuffix it by a number. E.g.: `shadow.3`. The first entry has no shuffix.
    - `nickname`: Resolves to the first-found character.
    - `cID`: Character Identification - the ID in the database.
- [ ] Track usage in chat and alike, to provide "Unplayed" status. Might notify a user if a char hasn't been used in a while, and if it isn't done by the user, might do something about it.

### Relationships (MakotoExtension)
Characters can relate to one another

- [ ] Create
- [ ] Delete
- [ ] Have the target character's owner verify the connection
- [ ] Display
    - The character being displayed has a "Relationships" page. It should list outgoing and incoming relationships.

### Fields
The creation and view page is distinctively sorted into tabs. Each entry here is a page, sub-entries are fields.

- [...] Wait for DragonsInn/Sapphy to provide a prototype.
- [ ] Allow a custom page of Markdown and CSS to provide additional detail.
- [ ] Allow posting a media to the general info to describe the character's theme.

### Sharing
A character may be shared between users

- [ ] Create
- [ ] Delete
- [ ] User must agree to sharing.

### Families and groups
Many characters may be associated to groups, families and the like.

#### Types of groups
- Family
- Clan

- [ ] Create
- [ ] Edit
- [ ] Delete
- [ ] Only characters that are at least of visibility "Community" can be enlisted

### Character images
Due to copyright concerns and artists having a DNP (Do Not Post) policy, these rules are especially applied to character image uploads. Violating one of these rules in one way or another results in a warning (ToS)

- [ ] Specify if image was done by yourself or not.
- [ ] Short description. Used as alt-text for blind people or if the image can't be loaded for now.
- If the image is by somebody else,
    - [ ] Specify location of original image
    - [ ] Specify artist by at least one source (Email is not allowed! Must use website or public site handle.)
        - Might parse the URL to provide a nicer display
- [ ] Specify if the image is clean or adult.

### RPG System
We might implement a system that allows a more-even roleplay experience. It is an opt-in feature by default.

#### Fields
To be discussed.

## Module: Chat
Where peeps hang out, talk, use characters, etc.

- [ ] Modernite `frug/ajax-chat`
    - [ ] Replace the Flash based audio by HTML5.
    - [ ] Replace the Ruby based socket client by SocketCluster - or a general interface.
    - [ ] Make the JavaScript module-aware (UMD)
    - [ ] Allow the templates to utilize PHP. Allows for custom menus and alike to be added through templative PHP tags.
    - [ ] Play a new specific sound on whisper.
    - [ ] Implement highlighted messages.
    - [ ] Play a new sound on receiving of highlighted message.
    - [ ] Use JSON instead of XML (much faster to parse!)
- [ ] Create the BIRD3 integration
    - [ ] Improve the `/`-command parser. Make it work like a CLI parser instead. In fact, might as well use one (:
    - Commands:
        - `/help`: Get a help!
        - `/highlight [message]`: Post a highlighted message (Mind. VIP)
        * `/wall [text]`: Send a text to every user currently online. (min. Moderator)
        * `/kick [userSpec] [timeSpec]`: Kick a user (by ID, username or display name) for a specified time ("3min" or something). (min. Moderator)
        * `/ban [userSpec] (--time=[time]) (--reason=[reason])`: Invoke the ban system on the user. Immediately logs the user out and permits them from visiting the site again. Optionally one can specify time and reason.
        * `/becomeBot`: All further messages and commands are sent AS the bot (Mrs. Drach). (Permission based)
        * `/becomeMe`: Revoke previous command.
        * `/changeToChar [characterSpec]`: Change to a character - by name, nickname or cID.
        * `/msg [userSpec] [message]`: Send a message to user.
        * `/ignore [userSpec]`: Ignore a user.
        * `/mute [userSpec]`: Globally mute a user. (min. Moderator)
        * `/move [userSpec] [channelSpec]`: Move a user to channel. (Forces move, no perm checking! Fails on non-existing channels.)
        * `/warn [userSpec] [text]`: Warn a user (a highlighted message but with additional CSS class. Plus increases the user's warn counter and shows it.)
        * `/channel [userSpec]`: Make a persistent conversation to the user. All messages are `/msg [userSpec] $message` by default then.
        * `/unchannel`: Revoke the last channel.
        * `/me [action]` or `/action [action]`: Do an action. This puts the current displayed name and the entire message into italic text and removes the preceding colon.
        * `/mes [action]` or `/actions [action]`: The same as above. But appends `'s` or `'` depending on the name ending with an "s" or not. Useful to describe things such as "Mo's feet hurt." using `/mes feet hurt.`
        * `/relate [toChar] [as]`: If currently being in use of a character, one can relate this character to another.
        * `/view (--user) [name]`: View the character or user if they are not IC (in character). Using `--user`, forces the user profile to be shown. (Opens in a new tab.)
        * `/roll NdS`: Roll N dices, where each has S sides.
        * `/join [channelSpec]`: Join a channel. It can contain spaces. Supplying only a part of the name results in a search. First hit will become target. If no channel is provided, automatically joins private channel.
        * `/invite [userSpec]`: Invite user to the current channel.
        * `/description ([channelSpec])`: If the current channel has one, print the description. Alternatively, if a channel is provided, show that one's instead.
        * `/edit ...`: Flag this current channel. Flags may be combined.
            * `--only=[role]`: Allow only this role to enter. Add a + at the end to allow anything and above.
            * `--perms=[perm1,perm2,...]`: Specify permissions for entering this channel.
            * `--description [text]`: Describe the room. Writes to database. Implies that only this and none other flag is used.
            * `--description-url=[url]`: Download the text from this URL and use it as description.
            * `--background=[url]`: An URL for setting this room's background image.
            * `--clear`: Uninvite all members but the owner from this channel. Only works on private channels.
        * `/audio [on/off/N]`: Control audio. Specify a number (with an optional percent-sign) to control volume.
        * `/char [characterSpec]` or `/changeToChar [cID]` (deprecated): Change to a character.
        * `// [message]`: Wrap the message in two parantheses. Example: `// foo` --> `(( foo ))`. Also known as the OOC command.
        * `/goooc` or `/reset` (deprecated): Go out of character
        * `/afk [reason]`: Go AFK
        * `/status [status]`: Adds a specific status to this user. No message results in clearing the status.
        * `/back`: Return from being AFK
        * `/quit`: Log out
    - [ ] Introduce soundpacks
        - Use Webpack to generate a soundpack list
        - use JSON to describe the soundpack
    - [ ] Use DIFM in favor of BBCode
    - [ ] Resolve @mentions into highlighted messages on the end-user (change message kind on client-side)
    - [ ] Allow uploading of files
        - A worker should watch over the file entries in the DB and delete them when timeout is hit.
        - A user is associated to uploads and can view their status in the chat.
    - [ ] Use Laravel to render views.
- [ ] Add KUTE.js for animations of new messages, etc.
- [ ] Provide new side panels for room descriptions and such.

## Feature: Notifications
A user should receive updates via email and on-site.

- [ ] Each time an entry is added to the `tbl_user_updates` table, an email notification should be sent
- [ ] Have a pool of workers dedicated to pumping emails to the users
- [ ] The updates are disposable. Eg, each update is a record. They can be deleted.
- [ ] If we ever get a mobile app, push APNs.
- [ ] Use desktop notifications, if allowed.

## Module: Forum
Discuss topics, or have long-going public RPs, and more.

### Structure
- Section
    - Board
        - Topics

For example:
- "Dragon's Inn stuff"
    - "New features"
        - "Feature X was updated"

- [ ] Create, edit, delete:
    - [ ] Section
    - [ ] Board
    - [ ] Topic
- [ ] Highlighted topics are always ontop and in different colours.

## Feature: Story
The story of the scene around the site, that people can derive from, imagine into, interact with, etc.

- [ ] Write a little story of the town.
- [ ] Describe the town, a bit like a typical Wikipedia article.
    - [ ] Use the infamous "CrapNet" as an ISP's name. Why not!

### Hotel
- [ ] Description
    - [ ] Rooms
    - [ ] Levels
    - [ ] Possibly, payment?

### Places
- [ ] Describe the places. They are dynamic, so in DB. (Perm: `place:create`)
- [ ] Edit descriptions (Perm: `place:edit`)
- [ ] Delete places and/or descriptions (Perm: `place:delete`)

### Jobs
- [ ] Create
- [ ] Edit
- [ ] Delete
- [ ] Associate to place
- [ ] Associate to character

## Module: Polls
Use these to ask dudes and dudettes of small things.

- [ ] Create
- [ ] Edit
- [ ] Delete
- [ ] View results
- [ ] Vote anonymously
- [ ] Show who voted what
- [ ] Make polls only accessible to...
    - [ ] Registered users
    - [ ] Specific roles

## Feature: Search
Searching things is important to discover things.

- [ ] Provide search.
- [ ] Search in all the media
- [ ] Show the search results in categories or uncategorized
- [ ] Allow advanced search
- [ ] Allow search via URI, which enables browsers like Chrome to save it as a search provider.

## Module: Terminal
A terminal visible within the chat or via SSH. Allows to manage things the NERDIEST WAY!

### Programs
- `character`: manage characters
- `chat`: interactive TUI for the chat.
- `blog`: Do a blog post
- `stats`: Get stats
- More to come.


# Stack configuration
The BIRD3 stack consists of a variety of layers.

## Backend (NodeJS)
- Language: ES6 (Babel), Uniter PHP
- Provides:
    * HTTP Server (SocketCluster, Express)
    * Event Server (SocketCluster)
    * RPC bridge to PHP (WebDriver, hprose)
    * Assets compiler (WebPack)
    * On-Demand JS transpiler (Express middleware, Babel)

## Backend (PHP)
- Language: PHP
- Provides:
    * Laravel App through RPC API

## Frontend
- Language: ES6, JSX, Uniter PHP
- Provides:
    * Event client (SocketCluster)
    * Dynamic widgets (JSX, o.o)
    * Meta library (o.o)

# API
BIRD3 should have an API, but it has to be discussed and described yet. The API layer will likely represent it's own little sub-application and probably run as a secondary app within the NodeJS server.

# Unit Tests
Unit tests have to be written for the specific target, and in the specific language.

# UI and UX (User Interface and User eXperience)
BIRD3's UI and UX have yet to be concepted and/or properly documented.

# Planned features
There are no real planned features except for the ones specced out above.
