

--
-- Структура таблицы `phpshop_modules_admlog_system`
--

DROP TABLE IF EXISTS `phpshop_modules_admlog_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_admlog_system` (
  `id` int(11) NOT NULL auto_increment,
  `enabled` enum('0','1') NOT NULL default '0',
  `serial` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;



INSERT INTO `phpshop_modules_admlog_system` VALUES (1,'0','');


DROP TABLE IF EXISTS `phpshop_modules_admlog_log`;
CREATE TABLE `phpshop_modules_admlog_log` (
  `id` int(11) NOT NULL auto_increment,
  `date` int(11) NOT NULL default '0',
  `user` varchar(255) NOT NULL default '',
  `ip` varchar(64) NOT NULL default '',
  `file` varchar(255) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `content` blob NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;