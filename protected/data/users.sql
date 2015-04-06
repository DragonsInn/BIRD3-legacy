/* Users */
CREATE TABLE IF NOT EXISTS `tbl_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(40) NOT NULL,
  `email` varchar(128) NOT NULL,
  -- Generated during registration.
  `activkey` varchar(128) NOT NULL,
  -- Admin, Moderator, VIP or User.
  `superuser` int(1) NOT NULL DEFAULT 0, -- User::R_USER
  -- Active or banned
  `status` int(1) NOT NULL DEFAULT 0, -- User::S_INACTIVE
  -- If the user should see developer contents.
  `developer` int(1) NOT NULL DEFAULT 0,
  -- This user is a supporter - meaning, he can receive feedback / complaint messages.
  `supporter` int(1) NOT NULL DEFAULT 0,
  `create_at` int NOT NULL,
  -- This value is to be set by the server.
  `lastvisit_at` int,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_username` (`username`),
  UNIQUE KEY `user_email` (`email`)
);

/* User Profile */
CREATE TABLE IF NOT EXISTS `tbl_user_profile` (
  `uID` int(11) NOT NULL,
  -- IM, Social Networks
  `skype` varchar(255),
  `steam` varchar(255),
  `psn` varchar(255),
  `xboxlife` varchar(255),
  `facebook` varchar(255),
  `twitter` varchar(255),
  `furaffinity` varchar(255),
  `sofurry` varchar(255),
  -- Bio. About the user.
  `about` text,
  -- Used to tell the file extension
  `avvie_ext` varchar(5),
  PRIMARY KEY (`uID`)
);

CREATE TABLE IF NOT EXISTS `tbl_user_settings` (
    `id` int(11) NOT NULL,
    -- User can see content marked as Adult.
    `adult` tinyint(1) NOT NULL DEFAULT 0, -- false. ;)
    -- User receives Newsletters.
    `newsletter` tinyint(1) NOT NULL DEFAULT 1,
    -- The user profile can be seen publicy.
    `public` tinyint(1) NOT NULL DEFAULT 1,
    -- The user's email is shown publicy.
    `showEmail` tinyint(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`)
);

-- note: Some of these features are automatically there for staff.
-- This can be useful in a few scenarios only.
CREATE TABLE IF NOT EXISTS `tbl_user_permissions` (
    `id` int(11) NOT NULL,
    -- Entries from that user's blog are also on the front page.
    `publicBlog` tinyint(1) NOT NULL DEFAULT 0,
    -- This user - only admins - may add, remove and assign jobs.
    `manageJobs` tinyint(1) NOT NULL DEFAULT 0,
    -- This user may edit the Place descriptions. (aka. You guys can fix my typos!)
    `editPlaces` tinyint(1) NOT NULL DEFAULT 0,
    -- This user has the previlege to edit other's characters. Wont be used, probably, but eh...
    `editChars` tinyint(1) NOT NULL DEFAULT 0,
    -- Same for media.
    `editMedia` tinyint(1) NOT NULL DEFAULT 0,
    -- And forum.
    `editFPosts` tinyint(1) NOT NULL DEFAULT 0,
    `editFTopics` tinyint(1) NOT NULL DEFAULT 0,
    `editFBoards` tinyint(1) NOT NULL DEFAULT 0,
    `editFSections` tinyint(1) NOT NULL DEFAULT 0,
    -- This user may activate or de-activate another user's Developer status. Good for error handling. Not for every mod.
    `editDev` tinyint(1) NOT NULL DEFAULT 0,
    -- This user can broadcast. /wall
    `canBroadcast` tinyint(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`)
);


/* Private messages */
CREATE TABLE IF NOT EXISTS `tbl_user_pm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_ID` int(11) NOT NULL, -- Sender
  `to_ID` int(11) NOT NULL, -- Reciever
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  PRIMARY KEY (`id`)
);

/* Conversations */
CREATE TABLE IF NOT EXISTS `tbl_user_pm_conv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mID` int(11) NOT NULL, -- MSG
  -- Response to mID if gt -1
  `response` int(11) NOT NULL DEFAULT -1,
  `composed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);

/* User Subscription
   Users can subscribe to other users.
   When a user does something its nodejs` task to pick it up and write an entry.
*/
CREATE TABLE IF NOT EXISTS `tbl_user_sub` (
  `sID` int(11) NOT NULL, -- subscriber
  `tID` int(11) NOT NULL -- target
);

CREATE TABLE IF NOT EXISTS `tbl_user_update` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  -- The user ment to receive this.
  `tID` int(11) NOT NULL,
  -- What kind of an update it is.
  `type` int(1) NOT NULL,
  -- Universal PK. It can reffer to a char, media or forum.
  `contentID` int(11) NOT NULL,
  `inserted` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);
