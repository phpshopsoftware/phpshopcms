
DROP TABLE IF EXISTS `phpshop_modules_cleversite_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_cleversite_system` (
  `id` int(11) NOT NULL auto_increment,
  `client` varchar(64) NOT NULL default '',
  `password` varchar(64) NOT NULL default '',
  `site` varchar(64) NOT NULL default '',
  `serial` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;



INSERT INTO `phpshop_modules_cleversite_system` VALUES (1,'','','','');