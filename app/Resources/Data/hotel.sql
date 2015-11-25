/* Character Jobs, in the hotel.
   A character can be associated to one of the hotel's jobs.

   ONE character has ONE job.
*/
CREATE TABLE IF NOT EXISTS `tbl_hotel_jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL,
  `where_id` int(11) NOT NULL, -- hotel_places SK
  `intro` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  PRIMARY KEY (`id`)
);

/* Places in Sa'Eti. That kinda mixes up with above... */
CREATE TABLE IF NOT EXISTS `tbl_hotel_places` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `desc` text NOT NULL,
  `image_url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
);

-- Sweet little things that Xynu can say.
CREATE TABLE IF NOT EXISTS `tbl_system_xynu` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    -- Who made it?
    `uID` int(11) NOT NULL,
    -- Say.
    `sentence` varchar(150) NOT NULL,
    PRIMARY KEY (`id`)
);
