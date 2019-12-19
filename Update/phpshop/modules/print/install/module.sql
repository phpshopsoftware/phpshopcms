-----------------------
-- PHPShop CMS Free
-- Module Install SQL
-----------------------


DROP TABLE IF EXISTS `phpshop_modules_print_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_print_system` (
  `id` int(11) NOT NULL auto_increment,
  `serial` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;



INSERT INTO `phpshop_modules_print_system` VALUES (1,'');

