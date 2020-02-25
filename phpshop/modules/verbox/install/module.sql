DROP TABLE IF EXISTS `phpshop_modules_verbox_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_verbox_system` (
  `id` int(11) NOT NULL auto_increment,
  `code` text default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO `phpshop_modules_verbox_system` VALUES (1,'');