

DROP TABLE IF EXISTS `phpshop_modules_sticker_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_sticker_system` (
  `id` int(11) NOT NULL auto_increment,
  `serial` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


INSERT INTO `phpshop_modules_sticker_system` VALUES (1,'');


DROP TABLE IF EXISTS `phpshop_modules_sticker_forms`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_sticker_forms` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(64) NOT NULL default '',
  `path` varchar(64) NOT NULL default '',
  `content` text NOT NULL,
  `mail` varchar(64) NOT NULL default '',
  `enabled` enum('0','1') NOT NULL default '1',
  `dir` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;