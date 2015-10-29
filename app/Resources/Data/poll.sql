-- Create a cute little poll.
-- Staff can make them. Maybe give Forum this feature in the future...
CREATE TABLE IF NOT EXISTS `tbl_poll` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `subject` varchar(500) NOT NULL,
    `question` varchar(1000) NOT NULL,
    PRIMARY KEY (`id`)
);

-- The options to a poll, very impurrtant.
CREATE TABLE IF NOT EXISTS `tbl_poll_options` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    -- The poll to which this reffers
    `pID` int(11) NOT NULL,
    `sentence` varchar(100) NOT NULL,
    PRIMARY KEY (`id`)
);

-- When a user votes, this is made
CREATE TABLE IF NOT EXISTS `tbl_poll_vote` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    -- Who voted?
    `uID` int(11) NOT NULL,
    -- For what?
    `oID` int(11) NOT NULL,
    -- in which poll?
    `pID` int(11) NOT NULL,
    -- When?
    `when` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);
