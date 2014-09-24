# The BIRD3 concept documentation
#### by Ingwie Phoenix, Dragon's Inn administrator, owner, lead developer.

The Dragon's Inn is based off a self-made CMS, based off the Yii framework (1.1.x), using an OOP oriented MVC aproach with enhanced abilities. Usually, MVC is *M*odel *V*iew *C*ontroller - however, Yii adds *Components* also. Those are "utility classes". Per-se, things that will be needed across the page, and do not belong into a controller, model or view, will go there.

The following description of the upcoming features for BIRD3 (BIRD 3.0) will not further mention concepts of MVC(C), Database management or the like. They are ment to clarify features, bug fixes that need to be done from BIRD2 (BIRD 2.5) as well as other information concerning the development, server structure and alike.



# Overall

## Design
The new design, codenamed "Epic-Eti" (play-off on the name "Sa'Eti"), will feature a lot of changes compared to the drag0n design used in current versions of BIRD. Foremost, it will focus more on the content, will be responsible (Desktop, Tablet, Phone) and feature animations - both, in the foreground and background.

### Menu
The top menu bar is to be replaced with three buttons - one to toggle a left, top and right panel, that shall be sliding out. This aproach is to be taken from the "NewDrag0n" design, originally written to be used with the "drag0n Package manager GUI". This aproach allows a minimal footprint of the actual menu. A user setting may allow to bring drag0n-style menus back in a future update.

### Panels

#### The top panel
The top panel contains the menu entries as listed:
- [Dragon's Inn logo]
- Informations
- The Hotel
- Community
- Creations
- [Paypal button for donating]

Upon clicking one of the entries, that shall be huge and click-able buttons visible in the panel, the other panels should disappear and give view to the following:
- Informations
  * The Team
  * Rules
- The Hotel
  * Story
  * Places
  * Jobs
- Community
  * Chat (NN)
  * Forum
  * Blogs
  * Users
- Creations
  * Characters
  * Galleries
    * Music
    * Art
    * Stories
  * Essays

#### The right panel
This panel serves as information panel. Users can log in here, access their settings, and quickly access things like the chat. This panel changes, depending on the view that they are visiting at the moment. The general information displayed is:

- Current version of BIRD
- If developer mode is turned on
- Who is logged in, a link to settings and to log out
- People in the chat (Public rooms only!) as well as a link to it.
- Number of own content. Names are click-able to visit the respective gallery or place (Characters, Music, Art, Stories, Essays, Blogs, Forum posts/threads)

The information varies, depending on the view. The most bottom part is the one that will change. The additional fields are as following:
- Main page, news page or such: Nothing new.
- Characters
  * Five recent characters, from you and from others.
- Forum
  * Recent posts that youre watching.

#### Left panel
The left panel is a multi-functional search field. Type something in, and it will search the site for things like art, music, characters, essays or the like. But, it also supports commands. Begin the search with a slash ("/") and type a command, to do the following things:

/char:
```
Character maintainer tool
Syntax: /char [action] [options] <Character name or cID to work with. Dismiss if creating.>

action:
    create :: Quickly create a new character
    edit   :: Edit a character
    delete :: Delete a character. Confirmation dialog will appear.

options:
    --name=NAME       :: Character full name. Use quotes.
    --nick=NICK       :: Nickname. Keep it short. If spaces or special characters, use quotes.
    --gender=[m/f/mh/fh/g]
                      :: Gender: Male, Female, Maleherm, Femaleherm, Genderless
    --orientation=[s/b/g/p/o/n]
                      :: Orientation: Straight, Bisexual, Gay, Pansexual, Omnisexual, Not interested
    --pic_url=URL     :: Upload picture from URL. Use multiple times.
    --public=[yes/no] :: Make this character public
    --adult=[yes/no]  :: Set adult flag
```

There are more commands to come. As you may noticed, this uses a UNIX-like aproach. See the *Chat* feature section for more!



## Interactivity
BIRD3 will feature an interactive kind of site. You may see notifications (if native thru your browser, or scripted with visual effects in JavaScript) if new content of your favorite people is available, or if some news have been posted. This is established by using `Socket.io`. This is a NodeJS module that enables interactive and live website development, by using the best protocol available for your browser, ranging from WebSockets down to ultra-old JS polling thru iFrames (yes, even older than AJAX). This is a site-wide feature and enables live actions and caching. In fact, your browser may save some information across pages. Therefore, however, the site will have to run off an optimized webserver. It is very likely that I am going to write my very own, in either C++ or NodeJS itself. The latter is more likely an option, but I will have to create PHP bindings into nodejs, to achieve the caching part.



## Caching
The site will no longer "just load" the pages. Rather, a caching method will keep track of changed and unchanged information, keeping much of it in memory, reducing the loading times to the speed of just using `sendfile()` on a regular HTML page. In laymans terms: ultra fast. But how is this done?

### Character pages are stored as plain HTML.
Once a character is created, a cached version of the actual information may be created in-memory as a JS object. This will enable the webserver to detect, if we're going to request a cached, or out-of-cache version of the character. Yes, some characters may go out of cache - if the server restarts, or if the character is pushed to the real end of the chain. Upon loading, this information will be injected into the PHP process as an extra variable. PHP may detect that it is set, and *SKIPS* the whole DB stuff, and goes to straightly display the page with the given information.

### Images thru CloudFlare
Character images may no longer be stored in the dragonsinn.tk domain itself, but rather on a new domain name. This domain name should then be given to Cloudflare to take note of, and to brutally cache everything. If a character image changes, the server may task a background worker to reset the CF cache, causing cloudflare to reload the image into its CDN.

### Multi-process working
As you just saw, some processes, as resetting CF cache, may be put into a new process. This includes the cache creation, Character and Chat cache maintenance and character image housekeeping. PHP processes may also be kept alive, but be semi-reset, to also keep a code-cache (OPCache). That way, if a script did not change, the previously compiled bytecode just becomes reused.



# Features

## Characters (CharaBase v3)
CharaBase is a module, previously written ontop the public and exported SMF API and now as a module in Yii, that allows the creation, management and sharing of characters. The last point is rather new and shall be explained in these next sections.

### The new character view
The new view will be tabbed. In fact, a bunch of new fields may be added to set a custom background, a thumbnail, and even a theme-song that can be played (will require copy-pasting YouTube links). The tabs (and fields) are:

- The person
  * Full name
  * Nickname
  * Importance (Main, Casual, New, Not played, Abandoned)
  * Birthplace
  * Birthdate
  * Profile language ?
  * Created at
  * Edited at
  * Owned by
  * Shared with
  * Was viewed *N* times
  * Profile layout (Pic-Only, Simple, Standart, Detailed, Advanced)
- Literature
  * History, can be very long
  * Footnotes with links to additional pages and places
- Personality
  * Personality description
  * Likes
  * Dislikes
- Adult (colorcoded in red)
  * Likes to be [Dominant/Submissive/Switch]
  * Prefers (...)
  * Additional information
- Gallery (Images, more themes, whatever)
- Relationships (NeptunusExtension, discussed later)
  * Automated relationship listing
  * Text field for additionals
- Other (big field of nothing. If not filled, this will not appear)

That results in these fields to be added, to the database, view and everything else:
- Character page image
- Theme song (YT link)
- Custom CSS (Yes, I will let you totally style some things yourself!!)

### Caring and Sharing
We are now introducing the ability to share a character, so you can care together! Both people will be able to play the character and edit it. But only the owner may delete it. You can also fully transfer a character. The reciever will be sent a message, to accept the transfer. You can stop such transfer also.

### Character exporting and importing
This feature has been long broken. But if you are leaving the site, or if you dont want a character on the site, but still want to keep all its information, you may export the character into a zip file - OR, export all the ones you own into a giant zip archive. There will be a HTML page with all the information, a RTF document, and a gigantig `.json` file, containing all the character information. You can import this file into the software again, to restore the character. The files are not user-bound! So you may pass the file to somebody else instead, and they restore the character. You can also easily create duplicates with that, if you want ot make twins or alike.

### Jobs at the Inn
Characters can, and its a special field, be associated to th ehotel itself. Quite nice, eh? :)

### NeptunusExtension: My dad is ..., my mom is ...
You can associate your character to others. This relationship is only one-sided. So make sure you make this change everywhere. In a later version, it may be automated. It also can handle clans and forms.

### NeptunusExtension: Forms
A form is similar to a character profile. Click a form, and the same character is reloaded, but with overwritten detail. This is then useful, when your form totally rewrites the character. An example would be the older version of a character. If you create a new form, you can take the current version as a template. Do that, and then edit the main character, to turn the old version into just a form.

### Clans/Families
Things like that, they exist. One of these is easily created, similar to a character, and in the same place also. A family/clan page just displays allt he members with their thumbnails, name and other minimal information. Useful for larger character palettes.


## Chat (AJAX Chat 0.9 BIRD-Edition)
I have contributed quite some ideas and fixes to this software. But now, i will take it to a whole new level. By using the interactive-site method, I can now decrease the load of database requests, as well as speeding up message loading and...communicating! New features are:

### Audio thru HTML5 with Flash fallback!
Are you on a phone, a tablet, or something without Adobe Flash 9? No problem! The new chat loads sounds thru HTML5's Audio API. That means, that you can kick the flash, and hear notifications as you multitask on your phone, tablet, or non-flash enabled desktop browser.

### Only the sounds you need.
Currently, flash downloads all of Excel's great soundpacks - about, what, 10mb? This is no longer the case. You open the page, and your lastly selected soundpack is choosen for you, and the soundfiles are downloaded - as MP3, OGG or WAV, depending on your browser, or if you are using the Flash fallback.

### Soundpacks (Developers, contributors!)
We now will have a better support for those. The soundpack information is going to be cached also in a JSON file. Delete this file, and the cache is re-created, including possibly changed information. To make a soundpack, create a folder, put all the sounds inside in MP3, OGG and WAV, create a package.json file, and fill in the details, that I will list later. Soundpacks can be contributed, uploaded and installed as Moderator and upwards.

### Terminal mode
Tick some checkbox, and you can use all the comands without a prepended /. This means, you could technically go with something like

    char create --name="King Legiza" --nick="K.Legi"

without leaving the chat. Thus, you will also see the returned output from the comand, that is very likely to be a link.

### Character lists refresh themselves.
Changed a char? The list refreshes with the next second!

### Private messages, fixed for good.
Tried to whisper, but whispered the worng person? Well, that was because bulky BBCode, and a broken PHP logic, were involved. Now, each user has more attributes that are visible to the chat. That means, that things like AFK status, IC status, and such, will no longer clutter the name. So, two characters may no longer share the same nickname. If they do, the cID is sneakily appended. You won't see it though, but if you whisper them, you will see soemthing like this:

    /msg Shadow#4943

This just means, that youre whispering the "Shadow" that you want to. Everything after the hashtag is not part of the actual name, but interpreted by the chat, making sure the message goes to the right person.

### Kicks and bans are different now. (Moderators, Admins)
`/kick NN` and `/ban Name` are different meanings. First will boot the user off the chat for a certain time. Latter will ban them, for ever. This sets their user account to status BANNED, blacklists their IP and hostname and applys other tricks.

### Room descriptions are pretty.
Hit a button, you will see the room description. OR, type `/rdesc` to see the *r*oom *desc*ription.

### Highlighted posts, when you got mentioned.
When you are ideling around, but someone mentions you, the post will be highlighted and marked for you, so you can pick it up easily. Set up your "hightlight tags". If one of those is found - processed client-side - then you see it highlighted. This is part of the user settings. In my case, I would use: Ing, Ingwie, burd.

### Whispers are noisy.
Got a whisper? Not just will it be highlighted, but a new sound (`whisper_recv`) will be emitted too.

### Images are smaller.
Images are thumbnailed and if you click them, you will get to see a nice box that hovers above, showing you the real deal.

### Hashtags start a online search.
Yeah, we use them. XD If you type soemthing like "#fml", you may notice it becomes highlighted, and is click-able. Random thing I cane up with.

### Mrs. Drach is always IC.
Meet the nice lady. Admins can take her over and "merge" into her. This is done by setting the bot-flag. The bot's userID becomes the last player's ID. If this player leaves the chat, the link will point to the "The Hotel" section of the site.

### File sharing
A new panel will allow you to share files. Its smart enough to take ID3 tags out of mp3 files and post them or post images as pictures, and alike. Files are deleted after a while. They are tracked in the backend server. A sub-process queries the cache - timeAdded and current tiemstamp - and deletes files accordingly.

### Colors!
You no longer are restricted to a set of colors - BUT you wil have a far biggr pre-made choice of these!



## Overhauled User module (YiiUser -> BIRDUser v1)
I am no longer going to use a third-party module for this. The new user mdoule will feature:

- Actually working OpenID registration.
- Private messaging
- Biography
- Better user pages
- Searchable characters in the profile
- IM fields (Skype, ...)
- Online status
- Last visited
- Registered at
- Displaying the various contents in tabs, similar to the character view.
- Users can subscribe to other users to follow on their content. These will be displayed in the left sidebar, if there is no search term or command entered! If you click an item, it disappears. Or you just click "read". The content updates live.

Users will also be searchable when composing a PM or when just opening the Users page int he menu.



## Galleries
Users may now host and use galleries for Music, Art and Stories. Essays, Blogs and Characters are external. However, they can be linked together. If you are creating a character image, you can use a submission in your gallery. Or, if you upload a picture, it will turn into a submission into your gallery. Uploading a picture however will make you accept the Upload Policy. In short words: If you dont own the image, credit the artist and the source. If you do not do this, the staff may delete the image immediately without a warning.



## Blogs
You wanna write about stuff? Okay. Do that! Blogs are collected in a single view. You can there advertise your art, or your help for stuff.



## Public API
Writing an Android/iOS app, or for whatever? You can take advantage of a RESTful API. Documentation for that will be available when you are a registered developer.



## Developer mode
You will see more information in the JavaScript console, as well as seeing a new link in the menu: Developer. There, you may observe the source tree, as well as entering PHP code and evaluate it live via a REPL. You also will see API documentation.



# Backend
The backend is hungry. Here are some requirements, as well as TODOs.

- WebSockets aware WebServer (either via NodeJS or C++)
- PHP bindings (with TSRM and Zend Maintainer ZTS enabled) to run in multiple threads with OPCache enabled, as well as curl and db extensions accordingly. Might use PDO+CouchDB or alike so both - nodejs, C++ and PHP - can access it.
- Lots of RAM.
- Lots of CPU to quickly process caches and the like.


## Everything is Markdown.
No longer will you need BBCode, everything willb e in Markdown, as much as possible. The only trail left, might be the color part. Otherwise, I am trying to abandon BBCode. Markdown is far easier to understand and use.


## Database structure
Due to learning about DB relationships, and now knowing what the `HAS_MANY`, `HAS_ONE` and `MANY_MANY` constants in Yii mean:

- `HAS_MANY`: The term used, when one dataset "has" many others. Example: One user has *many* posts.
- `HAS_ONE`: The term used, when one dataset can be associated to one other. Example: One character picture has *one* character profile, that it is associated to.
- `MANY_MANY`: The record having this, is actually a database representing a N-to-M relationship. An example: One truck driver can use many trucks. One truck can be used by many users. Its unlikely that the inn will have such relations, as they are more absolute.

### Tables
- tbl_characters
  * (PK) cID, int(11): The character ID.
  * (SK) uID, int(11): The userID. Points to tbl_users.id
  * category, tinyint(4)
  * position, tinyint(4)
  * scenario, tinyint(4)
  * (new) adult, int(1)
  * (new) private, int(1)
  * (new) theme_url, varchar(255)
  * (new) css, varchar(255) - must be purified. Probably use a CSS validator?
  * name, varchar(255)
  * nickName, varchar(255)
  * birthdate, varchar(8) - date. CActiveRecord will ensure its a date format.
  * birthplace, varchar(100)
  * sex, tinyint(4)
  * orientation, tinyint(4)
  * species, varchar(100)
  * (Deprecated) makeup, varchar(255)
  * (Deprecated) clothing, varchar(255)
  * (Deprecated) addit_appearance, varchar(255)
  * height, varchar(15)
  * weight, varchar(15)
  * eye_c, varchar(100)
  * eye_s, varchar(100)
  * hair_c, varchar(100)
  * hair_s, varchar(100)
  * hair_l, varchar(100)
  * history, text
  * (New) appearance, text
  * likes, text
  * dislikes, text
  * addit_desc, text
  * relationships, text
  * dom_sub, tinyint(4)
  * preferences, varchar(255) - Will become text
  * (Deprecated, merge with preferences) addit_adult, text
  * spirit_status, tinyint(4)
  * spirit_condition, tinyint(4)
  * spirit_alignment, tinyint(4)
  * spirit_sub_alignment, tinyint(4)
  * sprit_type, tinyint(4)
  * spirit_death_date, varchar(100)
  * spirit_death_place, varchar(8) - date
  * spirit_death_cause, varchar(255)

- (New) tbl_characters_forms
  * (PK) fID, int(11): The form ID.
  * (SK) cID, int(11): the character ID to which this is linked.
  * name, varchar(255)
  * nickName, varchar(255)
  * birthdate, varchar(8) - date. CActiveRecord will ensure its a date format.
  * birthplace, varchar(100)
  * sex, tinyint(4)
  * orientation, tinyint(4)
  * species, varchar(100)
  * height, varchar(15)
  * weight, varchar(15)
  * eye_c, varchar(100)
  * eye_s, varchar(100)
  * hair_c, varchar(100)
  * hair_s, varchar(100)
  * hair_l, varchar(100)
  * history, text
  * likes, text
  * dislikes, text
  * (New) appearance, text
  * addit_desc, text
  * relationships, text
  * dom_sub, tinyint(4)
  * preferences, varchar(255) - Will become text
  * spirit_status, tinyint(4)
  * spirit_condition, tinyint(4)
  * spirit_alignment, tinyint(4)
  * spirit_sub_alignment, tinyint(4)
  * sprit_type, tinyint(4)
  * spirit_death_date, varchar(100)
  * spirit_death_place, varchar(8) - date
  * spirit_death_cause, varchar(255)

- tbl_character_sharing
  * (PK) id, int(11): The id of the share
  * (SK) cID, int(11): ID of the shared character.
  * (SK) uID, int(11): ID of the user with who this is shared
  * token, varchar(100): A token used while verifying the share.
  * due_date, timestamp: When this share will go out. Taken out and apart by sub-process (DB maintainer)

- tbl_character_picture
  * (PK) id, int(11): ID of the image, used for link.
  * (SK) cID, int(11): Character to which this is associated
  - Note: Images are saved as $cID/$id. I could have just used $id. The filetype (mimetype) is determined automatically and set accordingly.

- tbl_character_relation_kind
  * (PK) rID, int(11): A relationship kind has an ID.
  * title, varchar(100): Name, possibly translated as keyword.
  * desc, varchar(500): Some infos.
  * origin, varchar(100)
  * target, varchar(100)
- tbl_character_relationships
  * (SK) from_cID, int(11)
  * (SK) to_cID, int(11)
  * (SK) kind, int(11)
  - Note: A character defines a relationship. The declaring character gets "origin", the target gets "target".

- tbl_users
  * (PK) id, int(11)
  * (SK) gID, int(11): Group
  * username, varchar(20) UNIQUE
  * password, varchar(128)
  * email, varchar(128) UNIQUE
  * activkey, varchar(128)
  * status, int(1)
  * (Deprecated) superuser, int(1)
  * create_at, timestamp
  * lastvisit_at, timestamp
  * email_public, int(1)
  * is_developer, int(1): Only admins can mark people as developers.
- tbl_user_profile (split, so the db loads faster)
  * (PK) uID, int(11)
  * devmode, int(1): If moderator or above, this can be turnt on.
  * bio, text
  * im_skype, varchar(50)
  * site_fa, varchar(50)
  * site_sf, varchar(50)
  * site_ib, varchar(50)
  * site_livestream, varchar(50)
  * site_twitch, varchar(50)
  * site_steam, varchar(50)
  * avatar, blob(1MB)
* tbl_user_groups
  * (PK) gID, int(11)
  * title, varchar(100)

- ajax_chat_(messages, online, channels) are as before. Except, that channels may be renamed to tbl_hotel_rooms.
- ajax_chat_kicks
  * (PK) userID, int(11)
  * ending_at, timestamp

- tbl_blacklist (the ban table)
  * ip, varchar(15)
  * hostname, varchar(255)
  * reason, text

- tbl_forum_sections
  * (PK) id, int(11): The id of the section, containing boards.
  * name, varchar(100): Is going to be sent thru the translation function. use a keyword and use the same in the translation table later.
- tbl_forum_boards
  * (PK) id, int(11)
  * (SK) s_id, int(11)
  * name, varchar(100)
  * description, varchar(500)
- tbl_forum_topic
  * (PK) id, int(11)
  * (SK) b_id, int(11): In which board this thing is
  * (SK) uID, int(11): The user who created this
  * title, varchar(100)
  * description, varchar(500)
  * created_at, timestamp
  * last_modified, timestamp
  * sticky, int(1)
- tbl_forum_post
  * (PK) id, int(11)
  * (SK) t_id, int(11)
  * (SK) uID, int(11)
  * message, text
  * edited_at, timestamp
  * edited_reason, varchar(255)
- tbl_forum_thumbsup
  * (SK) p_id, int(11)
  * (SK) uID, int(11)

- tbl_essays
  * (PK) id, int(11)
  * (SK) uID, int(11)
  * title, varchar(255)
  * summary, varchar(1000)
  * content, text
  * footnotes, text
  * createdAt, timestamp
  * modifiedAt, timestamp

- tbl_submission
  * (PK) id, int(11)
  * (SK) uID, int(11)
  * type, tinyint(4)
  * public, int(1)
  * adult, int(1)
  * createdAt, timestamp
  * editedAt, timestamp
  * title, varchar(100)
  * description, text
  * art_image_name, varchar(255): If this submission is art, the filename should go here.
  * story_content, text
  * music_file_name, varchar(255): Same as above.

- tbl_blog
  * (PK) id, int(11)
  * (SK) uID, int(11)
  * title, varchar(100)
  * content, text - This will be shortened for previews!
  * createdAt, timestamp
  * modifiedAt, timestamp

- tbl_themes
  * (PK) id, int(11)
  * (SK) uID, int(11)
  * name, varchar(100)
  * gstart, varchar(6)
  * gend, varchar(6)
  * shadow, varchar(6)

- tbl_hotel_jobs
  * (PK) id, int(11)
  * title, varchar(100)
- tbl_hotel_jobs_resolv
  * (SK) jID, int(11): The job ID.
  * (SK) cID, int(11): The Character associated.

- tbl_translate
  * (PK) id, int(11)
  * (SK) uID, int(11)
  * language, char(2)
  * from, varchar(255)
  * to, varchar(255)

- tbl_private_message
  * (PK) id, int(11)
  * (SK) from_uID, int(11)
  * (SK) to_uID, int(11)
  * title, varchar(100)
  * content, text

#### "My favorites" tables.
- tbl_submission_fave
  * (SK) uID, int(11)
  * (SK) sID, int(11)
- tbl_essay_fave
  * (SK) uID, int(11)
  * (SK) eID, int(11)
- tbl_character_fave
  * (SK) uID, int(11)
  * (SK) cID, int(11)


### DB relationships:
- A character...
  * Has one user.
  * Has many faves.
  * Has many relationships.
  * Has many images.
  * Has many jobs.
  * Has many forms.
  * Has many users that its shared with.
  * (Maybe, not concepted yet) Has many submissions associated.

- A relationship...
  * Has one origin, the character that declared the relationship.
  * Has one target, the character that was selected.
  - Example: Ingwie (origin)'s is Sasha (target). So, in Ingwie's profile, it'd read "Ingwie's pets: Sasha".
  ... In Sasha's, it'd read: "Sasha is a pet to: Ingwie".

- A user...
  * Has many characters.
  * Has one group.
  * Has many private messages.
  * Has many shared characters.
  * Has many blog entries.
  * Has many submissions.
  * Has many forum posts.
  * Has many favorite characters.
  * Has many favorite submissions.
  * Has many favorite essays.
  * Has many thumbs-up in the forum.
  * Has many boards in which he can admiister.
  * Has one profile.
  * Has many themes.
  * Has many AJAXChat messages.
  * Has one ajax_chat_kick.

- A user group...
  * Has many users.

- A Hotel job...
  * Many characters.

- A translation...
  * Has one user.

- A forum post...
  * Has one user.
  * Has many thumb-ups.
  * Has one topic associated.
    - which has one board associated.
      - which has one section associated.
        - which has one user associated.
- A thumbs-up in the forum...
  * Has one post.
  * Has one user.

- A submission...
  * Has one user.
  * Has many faves.

## Processes
The BIRD process itself spawns a lot of other processes or threads. [T] marks a thread, [CC] marks concurrent, [P] marks a process.

- [T] There will be a bunch of PHP threads with OPCache enabled, waiting to serve requests. I am thinking: Either turn it into processes and use Prefork, or write a C++ binding for Nodejs.
- [P] A MYSQL worker, that will keep track of timestamps and maintain things. For example, it will check for possible kick removes.
- [CC] The frontend cache will try to deal out as many pages as posible off static caches. It will do this by giving PHP additional details, so it skips database routines.
- [P] A character housekeeping process, that will schedule image cleanups and tell the CF process to udpate CDN caches.
- [P] A cloudflare process that will check, if anything needs to be cleaned. Sometimes, it will do a full clean.
- [T] The websockets thread will handle the chat, site updates and terminal mode.
- [P] A backup process that will ensure data savety, by spawning FTP uploads to third-party servers and the like. No userdata ever leaves the server.
- [T] A simple API worker thread. If the API link is called, this handles it. Either redirects to PHP instance, queries cache, or asks MYSQL itself.
- [T] Security thread. A worker that checks incoming requests. It also re-reads configuration files, if needed.
- [T] If configuration files change, github commits are available or things like that, it will message the parent process to stop. Then it will spawn a sub-process to automatically install updates and rerun. The OTA method might even allow the server to momentarily enable maintenance mode by itself, and restart.
