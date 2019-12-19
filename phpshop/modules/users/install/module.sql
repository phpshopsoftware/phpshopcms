
ALTER TABLE `phpshop_pages` ADD `user_security` enum('0','1') NOT NULL default '0';

DROP TABLE IF EXISTS `phpshop_modules_users_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_users_system` (
  `id` int(11) NOT NULL auto_increment,
  `flag` enum('0','1') NOT NULL default '1',
  `enabled` enum('0','1') NOT NULL default '1',
  `stat_flag` enum('0','1','2') NOT NULL default '1',
  `captcha` enum('0','1') NOT NULL default '1',
  `mail_check` enum('1','2') NOT NULL default '1',
  `serial` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


INSERT INTO `phpshop_modules_users_system` VALUES (1,'1','1','1','1','1','');


DROP TABLE IF EXISTS `phpshop_modules_users_users`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_users_users` (
  `id` int(11) NOT NULL auto_increment,
  `login` varchar(64) NOT NULL default '',
  `password` varchar(64) NOT NULL default '',
  `mail` varchar(64) NOT NULL default '',
  `enabled` enum('0','1') NOT NULL default '0',
  `date` varchar(64) NOT NULL default '0',
  `activation` varchar(64) NOT NULL default '',
 `content` blob NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


DROP TABLE IF EXISTS `phpshop_modules_users_log`;
CREATE TABLE `phpshop_modules_users_log` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL default '0',
  `user_name` varchar(64) NOT NULL default '',
  `date` varchar(11) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
