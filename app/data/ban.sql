CREATE TABLE IF NOT EXISTS `tbl_bans` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    -- Time when it ends
    `endTime` int(11) NOT NULL,
    -- Its infinite bro.
    `infinite` tinyint(1) NOT NULL,
    -- These two values can be empty - they are optional.
    -- If an active user was banned, this is set.
    `uID` int(11),
    -- This can optionally be specified.
    `reason` varchar(200),
    -- The following implements the actual ban system.
    `ip` varchar(16) NOT NULL,
    -- How long is a fingerprint?
    `fingerprint` varchar(100),
    PRIMARY KEY (`id`)
);
