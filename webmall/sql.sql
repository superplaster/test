CREATE TABLE IF NOT EXISTS `donate_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_name` varchar(255) NOT NULL,
  `item_price` int(11) NOT NULL,
  `item_count` varchar(255) NOT NULL,
  `item_id` varchar(255) NOT NULL,
  `item_ali` varchar(255) NOT NULL,
  `item_cat` int(11) NOT NULL,
  `item_pack` int(11) NOT NULL,
  `item_image` varchar(255) NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;