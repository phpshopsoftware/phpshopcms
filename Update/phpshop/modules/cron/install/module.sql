-----------------------
-- PHPShop CMS Free
-- Module Install SQL
-----------------------


DROP TABLE IF EXISTS `phpshop_modules_cron_log`;
CREATE TABLE `phpshop_modules_cron_log` (
  `id` int(11) NOT NULL auto_increment,
  `job_id` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `path` varchar(255) NOT NULL default '',
  `status` text NOT NULL,
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


DROP TABLE IF EXISTS `phpshop_modules_cron_job`;
CREATE TABLE `phpshop_modules_cron_job` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `path` varchar(255) NOT NULL default '',
  `used` enum('0','1') NOT NULL default '0',
  `last_execute` int(11) NOT NULL default '0',
  `execute_day_num` int(1) NOT NULL default '0',
  `enabled` enum('0','1') NOT NULL default '0',
  `num` tinyint(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 ;


DROP TABLE IF EXISTS `phpshop_modules_cron_system`;
CREATE TABLE `phpshop_modules_cron_system` (
  `id` int(11) NOT NULL auto_increment,
  `serial` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;



INSERT INTO `phpshop_modules_cron_system` VALUES (1,'');
