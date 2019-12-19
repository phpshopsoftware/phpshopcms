DROP TABLE IF EXISTS `phpshop_modules_envybox_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_envybox_system` (
  `id` int(11) NOT NULL auto_increment,
  `widget_id` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO `phpshop_modules_envybox_system` VALUES (1,'');