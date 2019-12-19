ALTER TABLE `phpshop_pages` ADD `comment_enabled` enum('0','1') NOT NULL default '1';
ALTER TABLE `phpshop_pages` ADD `rating_enabled` enum('0','1') NOT NULL default '1';

DROP TABLE IF EXISTS `phpshop_modules_comment_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_comment_system` (
  `id` int(11) NOT NULL auto_increment,
  `enabled` enum('0','1') NOT NULL default '1',
  `serial` varchar(64) NOT NULL default '',
  `flag` enum('0','1') NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


INSERT INTO `phpshop_modules_comment_system` VALUES (1,'1','','1');


DROP TABLE IF EXISTS `phpshop_modules_comment_log`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_comment_log` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `user` varchar(64) NOT NULL default '',
  `content` varchar(255) NOT NULL default '',
  `date` int(11) NOT NULL,
  `page` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


DROP TABLE IF EXISTS `phpshop_modules_comment_ratlog`;
CREATE TABLE `phpshop_modules_comment_ratlog` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL default '0',
  `rating` enum('1','2','3','4','5') NOT NULL default '1',
  `date` int(11) NOT NULL default '0',
  `page` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
