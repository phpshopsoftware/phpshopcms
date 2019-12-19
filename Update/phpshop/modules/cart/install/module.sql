

DROP TABLE IF EXISTS `phpshop_modules_cart_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_cart_system` (
  `id` int(11) NOT NULL default '0',
  `filedir` varchar(255) NOT NULL default '',
  `catdir` varchar(255) NOT NULL default '',
  `enabled` enum('0','1') NOT NULL default '0',
  `email` varchar(255) NOT NULL default '',
  `valuta` varchar(255) NOT NULL default '',
  `message` text NOT NULL,
  `enabled_market` enum('0','1') NOT NULL default '0',
  `num` int(11) NOT NULL default '0',
  `enabled_speed` enum('0','1') NOT NULL default '0',
  `enabled_search` enum('0','1') NOT NULL default '0',
  `serial` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


INSERT INTO `phpshop_modules_cart_system` VALUES ('1','','','1', 'admin@localhost', 'руб.', '<p><h4>Ваш заказ принят!</h4></p>','0',50,'0','0','');