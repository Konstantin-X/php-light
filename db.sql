-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(3) unsigned NOT NULL,
  `user_id` int(6) unsigned NOT NULL,
  `rate` int(1) unsigned NOT NULL,
  `text` text NOT NULL,
  `images` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `mail` varchar(30) NOT NULL,
  `name` varchar(20) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;