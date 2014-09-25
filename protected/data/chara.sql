CREATE TABLE IF NOT EXISTS `tbl_charabase` (
  `cID` int(11) NOT NULL AUTO_INCREMENT,
  `uID` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `edited_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_updated` timestamp NOT NULL,
  `last_played` timestamp NOT NULL,
  -- Main, Casual
  `importance` int(2) NOT NULL,
  -- New, Unpalyed, Abandoned
  `status` int(2) NOT NULL,
  -- Private, Community, Public
  `visibility` int(1) NOT NULL,
  -- Style for the profile. Basically the old scenario, really.
  `style` int(2) NOT NULL,
  -- Is it adult? bool
  `adult` int(1) NOT NULL,

  -- Basic but important details
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `nickName` varchar(255) COLLATE utf8_bin NOT NULL,
  `species` varchar(255) COLLATE utf8_bin NOT NULL,
  `sex` int(4) NOT NULL,
  `orientation` int(4) NOT NULL,
  `personality` text COLLATE utf8_bin NOT NULL,
  `birthday` varchar(20) NOT NULL,
  `birthPlace` varchar(255) COLLATE utf8_bin NOT NULL,

  -- Details
  `height` varchar(255) COLLATE utf8_bin NOT NULL,
  `weight` varchar(255) COLLATE utf8_bin NOT NULL,
  `eye_c` varchar(255) COLLATE utf8_bin NOT NULL,
  `eye_s` varchar(255) COLLATE utf8_bin NOT NULL,
  `hair_c` varchar(255) COLLATE utf8_bin NOT NULL,
  `hair_s` varchar(255) COLLATE utf8_bin NOT NULL,
  `hair_l` varchar(255) COLLATE utf8_bin NOT NULL,
  /* New */`bodyType` int(2) NOT NULL,
  `appearance` text NOT NULL,

  -- Spiritual
  `spirit_status` tinyint(4) NOT NULL,
  `spirit_condition` tinyint(4) NOT NULL,
  `spirit_alignment` tinyint(4) NOT NULL,
  `spirit_sub_alignment` tinyint(4) NOT NULL,
  `spirit_type` tinyint(4) NOT NULL,
  `spirit_death_date` varchar(20) NOT NULL,
  `spirit_death_place` varchar(255) COLLATE utf8_bin NOT NULL,
  `spirit_death_cause` varchar(255) COLLATE utf8_bin NOT NULL,

  -- Literature
  `history` text COLLATE utf8_bin NOT NULL,
  `likes` text COLLATE utf8_bin NOT NULL,
  `dislikes` text COLLATE utf8_bin NOT NULL,
  `addit_desc` text COLLATE utf8_bin NOT NULL,

  -- Adult
  `dom_sub` int(2) NOT NULL,
  `preferences` text COLLATE utf8_bin NOT NULL,

  -- Displayed within the image tab
  `artistNote` text NOT NULL,

  -- The big other. If empty, not displayed.
  `other_title` varchar(50) NOT NULL,
  `other` text NOT NULL,
  -- CSS code to be headed to the header
  -- Need a purifier here.
  `css` text NOT NULL,
  -- YT link. Should auto-generate.
  `theme_type` int(4) NOT NULL,
  `theme` varchar(255) NOT NULL,
  PRIMARY KEY (`cID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `tbl_charabasePictures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oID` int(11) NOT NULL, -- SK. "Object ID". Character, Association
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `desc` text COLLATE utf8_bin NOT NULL,
  -- Defines if this is for a character, form or association.
  `type` int(5) NOT NULL,
  -- 4GB. The fur that fills that gets a cake.
  `data` longblob NOT NULL,
  -- Last modified. Important for NodeJS cache.
  `modified` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Character relationships:
   Example: Ceinios is Mo's pet.

        Mo --> Ceinios = Master
        Ceinios --> Mo = Pet

    Two characters are mated.

        Excel --> Rina = Mate
        Rina --> Excel = Mate
*/
CREATE TABLE IF NOT EXISTS `tbl_charabaseRelationship` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  -- Subject
  `s_id` int(11) NOT NULL,
  -- Target
  `t_id` int(11) NOT NULL,
  -- Type of relationship
  `type` int(5) NOT NULL,
  -- Confirmed?
  `confirmed` int(1) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `tbl_characterRelationship_Type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
);


/* Character Forms
   ONE character HAS MANY forms.
   ONE form HAS ONE character.
*/
CREATE TABLE IF NOT EXISTS `tbl_charabaseForm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cID` int(11) NOT NULL, -- SK
  `name` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  PRIMARY KEY (`id`)
);


/* Character Association
   A family, clan, or whatever.
*/
CREATE TABLE IF NOT EXISTS `tbl_charabaseAssociation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `summary` varchar(500) NOT NULL,
  `details` text NOT NULL,
  PRIMARY KEY (`id`)
);

/* Character Association Relation

        ONE character HAS MANY associations
        ONE association HAS MANY characters
*/
CREATE TABLE IF NOT EXISTS `tbl_charabase_AssocRel` (
    `cID` int(11) NOT NULL,
    `aID` int(11) NOT NULL
);

/* Character Sharing
   User 1 can share their char with user 2.

    ONE user (shares with) MANY users
*/
CREATE TABLE IF NOT EXISTS `tbl_charabase_ShareRel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  -- The sharing user and their char
  `cID` int(11) NOT NULL,
  -- Target user
  `tID` int(11) NOT NULL,
  -- When was this inserted? Important for nodejs worker.
  `inserted` timestamp NOT NULL,
  -- Active or not?
  `active` int(1) NOT NULL,
  PRIMARY KEY (`id`)
);


/* Character faves

        ONE character HAS MANY faves
        ONE fave has HAS MANY characters
*/
CREATE TABLE IF NOT EXISTS `tbl_charabaseFaves` (
    `uID` int(11) NOT NULL,
    `cID` int(11) NOT NULL,
    `faved_at` int NOT NULL
);

/* Characters linked to Media */
CREATE TABLE IF NOT EXISTS `tbl_charabase_MediaRel` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `mID` int(11) NOT NULL,
    `cID` int(11) NOT NULL,
    PRIMARY KEY (`id`)
);
