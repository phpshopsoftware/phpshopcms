DROP TABLE IF EXISTS `phpshop_modules_snow_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_snow_system` (
  `id` int(11) NOT NULL auto_increment,
  `flag` enum('1','2') NOT NULL default '1',
  `color` varchar(64) NOT NULL default '',
  `serial` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;



INSERT INTO `phpshop_modules_snow_system` VALUES (1,'1','#AAAACC','');