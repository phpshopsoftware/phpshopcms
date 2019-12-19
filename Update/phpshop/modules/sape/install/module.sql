-----------------------
-- PHPShop CMS Free
-- Module Install SQL
-----------------------


DROP TABLE IF EXISTS `phpshop_modules_sape`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_sape` (
  `id` int(11) NOT NULL default '0',
  `sape_user` varchar(64) NOT NULL default '',
  `title` varchar(64) NOT NULL default '',
  `enabled` enum('0','1') NOT NULL default '0',
  `flag` enum('0','1') NOT NULL default '0',
  `num` int(11) NOT NULL default '5',
  `serial` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


INSERT INTO `phpshop_modules_sape` VALUES ('1','4cb48833f491686a2500f80310e072da','Sape','1','0','3','');

