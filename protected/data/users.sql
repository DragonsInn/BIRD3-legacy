/* Users */
CREATE TABLE IF NOT EXISTS `tbl_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL DEFAULT ``,
  `password` varchar(128) NOT NULL DEFAULT ``,
  `email` varchar(128) NOT NULL DEFAULT ``,
  `activkey` varchar(128) NOT NULL DEFAULT ``,
  `superuser` int(1) NOT NULL DEFAULT `0`,
  `status` int(1) NOT NULL DEFAULT `0`,
  `developer` int(1) NOT NULL,
  `create_at` int(11) NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastvisit_at` int(11) NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_username` (`username`),
  UNIQUE KEY `user_email` (`email`)
);

/* User Profile */
CREATE TABLE IF NOT EXISTS `tbl_user_profile` (
  `uID` int(11) NOT NULL,
  -- IM, Social Networks
  `skype` varchar(255) NOT NULL,
  `steam` varchar(255) NOT NULL,
  `psn` varchar(255) NOT NULL,
  `xboxlife` varchar(255) NOT NULL,
  `facebook` varchar(255) NOT NULL,
  `twitter` varchar(255) NOT NULL,
  `furaffinity` varchar(255) NOT NULL,
  `sofurry` varchar(255) NOT NULL,
  -- Bios
  `about` text NOT NULL,
  -- Avvie
  `avatar` longblob NOT NULL,
  PRIMARY KEY (`uID`)
);

/* Private messages */
CREATE TABLE IF NOT EXISTS `tbl_user_pm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  PRIMARY KEY (`id`)
);

/* Conversations */
CREATE TABLE IF NOT EXISTS `tbl_user_pm_conv` (
  `sID` int(11) NOT NULL, -- Sender
  `rID` int(11) NOT NULL, -- Reciever
  `mID` int(11) NOT NULL, -- MSG
  -- Response to mID if gt -1
  `response` int(11) NOT NULL DEFAULT 0,
  -- Timestap = a long int! :D So, make it a PK
  -- YII must set this!
  `composed` int NOT NULL,
  PRIMARY KEY (`composed`)
);

/* User Subscription
   Users can subscribe to other users.
   When a user does something its nodejs` task to pick it up and write an entry.
*/
CREATE TABLE IF NOT EXISTS `tbl_user_sub` (
  `sID` int(11) NOT NULL,
  `tID` int(11) NOT NULL
);

CREATE TABLE IF NOT EXISTS `tbl_user_update` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  -- The user ment to receive this.
  `tID` int(11) NOT NULL,
  `type` int(1) NOT NULL,
  -- Universal PK
  `contentID` int(11) NOT NULL,
  `inserted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `tbl_user_settings` (
    `id` int(11) NOT NULL,
    `adult` tinyint(1) NOT NULL,
    `newsletter` tinyint(1) NOT NULL,
    `public` tinyint(1) NOT NULL,
    `showEmail` tinyint(1) NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `tbl_user_permissions` (
    `id` int(11) NOT NULL,
    `publicBlog` tinyint(1) NOT NULL,
    `manageJobs` tinyint(1) NOT NULL,
    PRIMARY KEY (`id`)
);
