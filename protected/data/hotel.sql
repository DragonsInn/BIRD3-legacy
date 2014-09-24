/* Character Jobs, in the hotel.
   A character can be associated to one of the hotel's jobs.
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

/* character Job association */
CREATE TABLE IF NOT EXISTS `tbl_hotel_JobRel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cID` int(11) NOT NULL,
  `jID` int(11) NOT NULL,
  -- Bool. If false, this job is not yet active.
  `active` int(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
);
