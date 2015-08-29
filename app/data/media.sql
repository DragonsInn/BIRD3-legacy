-- This is a "unified" thing.
CREATE TABLE IF NOT EXISTS `tbl_media_submission` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `uID` int(11) NOT NULL,
    `title` varchar(50) NOT NULL,
    `desc` text NOT NULL,
    `public` int(1) NOT NULL,
    `adult` int(1) NOT NULL,

    -- Now come the specific parts.

    -- Song: Song's length in ms.
    -- Story/Essay: Length in words.
    `length` int NOT NULL,

    -- Art, Song: Filesize, extension and hash
    `size` int NOT NULL,
    `ext` varchar(10) NOT NULL,
    `sha1_hash` char(40) NOT NULL,

    -- Type (Art, Music, Essay)
    `type` int(5) NOT NULL,
    PRIMARY KEY (`id`)
);

-- A bit of a commentar.
CREATE TABLE IF NOT EXISTS `tbl_media_comment` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `mID` int(11) NOT NULL, -- Media
    `uID` int(11) NOT NULL, -- User
    -- If greater than 0, this actually is a response.
    `respondTo` int(11) NOT NULL DEFAULT 0,
    `content` varchar(500) NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `tbl_media_faves` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `mID` int(11) NOT NULL,
    `uID` int(11) NOT NULL,
    `faved_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `tbl_media_rating` (
    `mID` int(11) NOT NULL,
    `uID` int(11) NOT NULL,
    `rating` int(5) NOT NULL
);
