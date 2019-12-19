
DROP TABLE IF EXISTS `phpshop_modules_messageboard_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_messageboard_system` (
  `id` int(11) NOT NULL auto_increment,
  `enabled` enum('0','1') NOT NULL default '1',
  `serial` varchar(64) NOT NULL default '',
  `flag` enum('0','1') NOT NULL default '1',
  `num` int(11) NOT NULL default '0',
 `enabled_menu` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


INSERT INTO `phpshop_modules_messageboard_system` VALUES (1,'1','','1',40,'0');


DROP TABLE IF EXISTS `phpshop_modules_messageboard_log`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_messageboard_log` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `date` int(11) NOT NULL default '0',
  `name` varchar(32) NOT NULL default '',
  `mail` varchar(32) NOT NULL default '',
  `title` text NOT NULL,
  `content` text NOT NULL,
  `tel` text NOT NULL,
  `enabled` enum('0','1') NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;