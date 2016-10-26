CREATE TABLE IF NOT EXISTS `products` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `img` text NOT NULL,
  `title` text NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- Dumping data for table `products`
INSERT INTO `products` (`id`, `img`, `title`, `text`) VALUES
(1, 'img1.jpg', 'Product1', 'Product1 desciption'),
(2, 'img2.jpg', 'Product2', 'Product2 desciption');

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(3) unsigned NOT NULL,
  `user_id` int(6) unsigned NOT NULL,
  `rate` int(1) unsigned NOT NULL,
  `text` text NOT NULL,
  `images` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mail` (`mail`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `mail` varchar(30) NOT NULL,
  `pass` varchar(32) NOT NULL,
  `token` varchar(32) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;