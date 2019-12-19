DROP TABLE IF EXISTS `phpshop_modules_jivosite_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_jivosite_system` (
  `id` int(11) NOT NULL auto_increment,
  `email` varchar(64) NOT NULL,
  `userPassword` varchar(64) NOT NULL,
  `display_name` varchar(64) NOT NULL,
  `widget_id` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO `phpshop_modules_jivosite_system` VALUES (1,'', '', '', '');