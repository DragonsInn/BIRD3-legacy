CREATE TABLE IF NOT EXISTS `tbl_media_art` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `uID` int(11) NOT NULL,
    `title` varchar(50) NOT NULL,
    `desc` text NOT NULL,
    `adult` int(1) NOT NULL,
    `public` int(1) NOT NULL,
    -- Verify using fnmatch()...
    `sha1_hash` char(40) NOT NULL,
    `ext` varchar(10) NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `tbl_media_music` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `uID` int(11) NOT NULL,
    `title` varchar(50) NOT NULL,
    `desc` text NOT NULL,
    `length` int NOT NULL,
    `public` int(1) NOT NULL,
    `adult` int(1) NOT NULL,
    -- Verify using fnmatch()...
    `sha1_hash` char(40) NOT NULL,
    `ext` varchar(10) NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `tbl_media_essay` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `uid` int(11) NOT NULL,
    `title` varchar(50) NOT NULL,
    `content` text NOT NULL,
    `public` int(1) NOT NULL,
    `adult` int(1) NOT NULL,
    PRIMARY KEY (`id`)
);


CREATE TABLE IF NOT EXISTS `tbl_media_comment` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    -- Universal comment table. o.o
    `forType` int(2) NOT NULL,
    `mID` int(11) NOT NULL,
    `uID` int(11) NOT NULL,
    -- If greater than 0, this actually is a response.
    `respondTo` int(11) NOT NULL DEFAULT 0,
    `content` text NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `tbl_media_faves` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `mID` int(11) NOT NULL,
    `uID` int(11) NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `tbl_media_rating` (
    ``
);
