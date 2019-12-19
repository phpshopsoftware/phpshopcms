
DROP TABLE IF EXISTS `phpshop_modules_blog_log`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_blog_log` (
  `id` int(64) NOT NULL AUTO_INCREMENT,
  `date` varchar(32) NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;


DROP TABLE IF EXISTS `phpshop_modules_blog_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_blog_system` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(64) NOT NULL,
  `enabled` enum('0','1') NOT NULL default '1',
  `serial` varchar(64) NOT NULL default '',
  `flag` enum('0','1') NOT NULL default '1',
  `num` int(11) NOT NULL default '0',
 `enabled_menu` enum('0','1') NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


INSERT INTO `phpshop_modules_blog_system` VALUES (1,'Blog','1','','1',40,'0');