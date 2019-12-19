-----------------------
-- PHPShop CMS Free
-- Module Install SQL
-----------------------


DROP TABLE IF EXISTS `phpshop_modules_forumlastpost`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_forumlastpost` (
  `id` int(11) NOT NULL default '0',
  `enabled` enum('0','1') NOT NULL default '0',
  `flag` enum('0','1') NOT NULL default '0',
  `path` varchar(255) NOT NULL default '',
  `height` int(11) NOT NULL default '0',
  `width` int(11) NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `num` int(11) NOT NULL default '5',
  `connect` enum('0','1') NOT NULL default '0',
  `serial` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


INSERT INTO `phpshop_modules_forumlastpost` VALUES ('1','1','0', 'http://forum.phpshopcms.ru', '330', '200','Форум','5','0','');

