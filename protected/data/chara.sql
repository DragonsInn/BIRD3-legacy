CREATE TABLE IF NOT EXISTS `tbl_charabase` (
  `cID` int(11) NOT NULL AUTO_INCREMENT,
  `uID` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `edited_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_played` timestamp NOT NULL,
  -- Main, Casual
  `importance` int(2) NOT NULL,
  -- New, Unpalyed, Abandoned
  `status` int(2) NOT NULL,
  -- Private, Community, Public
  `visibility` tinyint(1) NOT NULL,
  -- Is it adult? bool
  `adult` tinyint(1) NOT NULL DEFAULT 1, -- true. Just be careful.

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
  -- `spirit_status` tinyint(4) NOT NULL,
  -- `spirit_condition` tinyint(4) NOT NULL,
  -- `spirit_alignment` tinyint(4) NOT NULL,
  -- `spirit_sub_alignment` tinyint(4) NOT NULL,
  -- `spirit_type` tinyint(4) NOT NULL,
  -- `spirit_death_date` varchar(20) NOT NULL,
  -- `spirit_death_place` varchar(255) COLLATE utf8_bin NOT NULL,
  -- `spirit_death_cause` varchar(255) COLLATE utf8_bin NOT NULL,

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

  -- The big other (page). If empty, not displayed.
  `other_title` varchar(50) NOT NULL,
  -- The contents of this page is Markdown.
  `other` text NOT NULL,
  -- CSS code to be headed to the header
  -- Need a purifier here.
  `css` text NOT NULL,
  -- YT link. Should auto-generate.
  `theme_type` int(4) NOT NULL,
  `theme` varchar(255) NOT NULL,

  -- Externals
  -- The job that this char is associated to.
  `jID` int(11) NOT NULL,
  PRIMARY KEY (`cID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- One character/association has many of these.
CREATE TABLE IF NOT EXISTS `tbl_charabasePictures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oID` int(11) NOT NULL, -- SK. "Object ID". Character, Association
  -- This is optional.
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  -- This should be checked against due to tagging stuff.
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
  -- To be displayed in the listing.
  `name` varchar(50) NOT NULL,
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
CREATE TABLE IF NOT EXISTS `tbl_charabase_AssocRel` (
    -- This is the character
    `cID` int(11) NOT NULL,
    -- that is related to this association.
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
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
);


/* Character faves

        ONE character HAS MANY faves
        ONE fave has HAS MANY characters
*/
CREATE TABLE IF NOT EXISTS `tbl_charabaseFaves` (
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
CREATE TABLE IF NOT EXISTS `tbl_charabase_MediaRel` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    -- The media.
    `mID` int(11) NOT NULL,
    -- The character
    `cID` int(11) NOT NULL,
    PRIMARY KEY (`id`)
);
