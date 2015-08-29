CREATE TABLE IF NOT EXISTS `tbl_characters` (
  `cID` int(11) NOT NULL AUTO_INCREMENT,
  `uID` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `edited_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_played` timestamp,
  -- Main, Casual
  `importance` int(2) NOT NULL,
  -- New 0, OK 1, Abandoned 2
  `status` int(2) NOT NULL DEFAULT 0,
  -- Private, Community, Public
  `visibility` tinyint(1) NOT NULL,
  -- Is it adult? bool
  `adult` tinyint(1) NOT NULL,

  -- Basic but important details
  `name` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `nickName` varchar(255) COLLATE utf8_general_ci,
  `species` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `sex` int(4) NOT NULL,
  `orientation` int(4) NOT NULL,
  `personality` text,
  `birthday` varchar(20),
  `birthPlace` varchar(255),

  -- Details
  `height` varchar(255),
  `weight` varchar(255),
  `eye_c` varchar(255),
  `eye_s` varchar(255),
  `hair_c` varchar(255),
  `hair_s` varchar(255),
  `hair_l` varchar(255),
  `bodyType` int(2), -- New.
  `appearance` text,

  -- Spiritual. Deprecated, but needs to be merged...
  -- `spirit_status` tinyint(4) NOT NULL,
  -- `spirit_condition` tinyint(4) NOT NULL,
  -- `spirit_alignment` tinyint(4) NOT NULL,
  -- `spirit_sub_alignment` tinyint(4) NOT NULL,
  -- `spirit_type` tinyint(4) NOT NULL,
  -- `spirit_death_date` varchar(20) NOT NULL,
  -- `spirit_death_place` varchar(255) COLLATE utf8_bin NOT NULL,
  -- `spirit_death_cause` varchar(255) COLLATE utf8_bin NOT NULL,

  -- Literature
  `history` text,
  `likes` text,
  `dislikes` text,
  `addit_desc` text,

  -- Adult
  `dom_sub` int(2),
  `preferences` text,

  -- Displayed within the image tab
  `artistNote` text,

  -- I call this useful.
  `font_color` varchar(10),
  -- The big other (page). If empty, not displayed.
  `other_title` varchar(50),
  -- The contents of this page is Markdown.
  `other` text,
  -- CSS code to be headed to the header
  -- Need a purifier here.
  `css` text,
  -- YT link. Should auto-generate.
  `theme_type` int(4),
  `theme` varchar(255),

  -- Externals
  -- The job that this char is associated to.
  -- if(jID==-1): No job.
  `jID` int(11) NOT NULL DEFAULT -1,
  PRIMARY KEY (`cID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- One character/association has many of these.
CREATE TABLE IF NOT EXISTS `tbl_characterPictures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oID` int(11) NOT NULL, -- SK. "Object ID". Character, Association
  -- This is optional.
  `name` varchar(255) COLLATE utf8_general_ci NOT NULL,
  -- This should be checked against due to tagging stuff.
  `desc` text NOT NULL,
  -- Defines if this is for a character, form or association.
  `type` int(5) NOT NULL,
  -- 4GB. The fur that fills that gets a cake.
  -- `data` longblob NOT NULL,
  -- Data will be stored in cdn/content/character_pictures
  -- Last modified. Important for NodeJS cache.
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
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
CREATE TABLE IF NOT EXISTS `tbl_characterRelationship` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  -- Subject
  `s_id` int(11) NOT NULL,
  -- Target
  `t_id` int(11) NOT NULL,
  -- Type of relationship. FK for tbl_characterRelationship_type.id
  `type` int(11) NOT NULL,
  -- Confirmed?
  `confirmed` tinyint(1) NOT NULL DEFAULT 0, -- false
  PRIMARY KEY (`id`)
);

/* There can be multiple types of relationships.
   Example:

   id   |   title
   0    |   Mother
   1    |   Father
   2    |   Son
   3    |   Daughter
   4    |   Wife
   5    |   Husband

   An example:
   Mo(cID:92) -[Relationship: type(husband)]> Moon(cID:460)

   Mo is of cID 92, Moon 460.
   They are linked through their relationship with ID N.
   N.type is set to 5.

   That results into:
   Mo is Moon's Husband.
   Dayori is Mo's son.
*/
CREATE TABLE IF NOT EXISTS `tbl_characterRelationship_Type` (
  -- SK for tbl_charabaseRelationship.type
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(20) COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`)
);


/* Character Forms
   ONE character HAS MANY forms.
   ONE form HAS ONE character.
*/
CREATE TABLE IF NOT EXISTS `tbl_characterForm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cID` int(11) NOT NULL, -- SK
  `name` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `desc` text NOT NULL,
  PRIMARY KEY (`id`)
);


/* Character Association
   A family, clan, or whatever.
*/
CREATE TABLE IF NOT EXISTS `tbl_characterAssociation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  -- To be displayed in the listing.
  `name` varchar(50) COLLATE utf8_general_ci NOT NULL,
  -- A rough summary of what it holds.
  `summary` varchar(500) NOT NULL,
  -- The full details.
  `details` text NOT NULL,
  PRIMARY KEY (`id`)
);

/* Character Association Relation

        ONE character HAS MANY associations
        ONE association HAS MANY characters
*/
CREATE TABLE IF NOT EXISTS `tbl_characterAssociation_Rel` (
    -- This is the character
    `cID` int(11) NOT NULL,
    -- that is related to this association.
    `aID` int(11) NOT NULL
);

/* Character Sharing
   User 1 can share their char with user 2.

    ONE user (shares with) MANY users
*/
CREATE TABLE IF NOT EXISTS `tbl_characterShare_Rel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  -- The sharing user and their char
  `cID` int(11) NOT NULL,
  -- Target user
  `tID` int(11) NOT NULL,
  -- When was this inserted? Important for nodejs worker.
  `inserted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  -- Active or not?
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
);


/* Character faves

        ONE character HAS MANY faves
        ONE fave has HAS MANY characters
*/
CREATE TABLE IF NOT EXISTS `tbl_characterFaves` (
    -- This has an ID for the listing
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `uID` int(11) NOT NULL,
    `cID` int(11) NOT NULL,
    `faved_at` timestamp NOT NULL,
    PRIMARY KEY (`id`)
);

/* Characters linked to Media
   One character can be linked to many medias.
   One media can have many characters linked to it.

   I 'love' N2N relations...
*/
CREATE TABLE IF NOT EXISTS `tbl_characterMedia_Rel` (
    -- The media.
    `mID` int(11) NOT NULL,
    -- The character
    `cID` int(11) NOT NULL,
    PRIMARY KEY (`id`)
);
