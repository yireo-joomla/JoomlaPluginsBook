CREATE TABLE IF NOT EXISTS `#__usergroup_fields` (
  `usergroup_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  UNIQUE KEY `usergroup_id` (`usergroup_id`)
);
