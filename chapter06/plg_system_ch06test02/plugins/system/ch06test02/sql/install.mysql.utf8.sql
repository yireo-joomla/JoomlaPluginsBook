CREATE TABLE IF NOT EXISTS `#__affiliate_requests` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `affiliate_id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `referer` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
   PRIMARY KEY (`id`)
);
