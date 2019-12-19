
DROP TABLE IF EXISTS `phpshop_modules_guard_crc`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_guard_crc` (
  `id` int(11) NOT NULL auto_increment,
  `log_id` int(11) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  `crc_name` varchar(255) NOT NULL default '',
  `crc_file` varchar(255) NOT NULL default '',
  `path_file` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `log_id` (`log_id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


DROP TABLE IF EXISTS `phpshop_modules_guard_log`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_guard_log` (
  `id` int(11) NOT NULL auto_increment,
  `date` int(11) NOT NULL default '0',
  `change_files` int(11) NOT NULL default '0',
  `new_files` varchar(11) NOT NULL default '0',
  `infected_files` varchar(11) NOT NULL default '0',
  `time` float NOT NULL default '0',
  `backup` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


DROP TABLE IF EXISTS `phpshop_modules_guard_signature`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_guard_signature` (
  `id` int(11) NOT NULL auto_increment,
  `virus_name` varchar(255) NOT NULL default '',
  `virus_signature` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


INSERT INTO `phpshop_modules_guard_signature` VALUES (1, 'PHP Shell Links Loader A', '@include_once');
INSERT INTO `phpshop_modules_guard_signature` VALUES (2, 'PHP Shell B', 'preg_replace');


DROP TABLE IF EXISTS `phpshop_modules_guard_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_guard_system` (
  `id` int(11) NOT NULL auto_increment,
  `enabled` enum('0','1') NOT NULL default '0',
  `stop` enum('0','1') NOT NULL default '0',
  `used` enum('0','1') NOT NULL default '0',
  `chek_day_num` int(1) NOT NULL default '0',
  `last_crc` int(11) NOT NULL default '0',
  `last_chek` int(11) NOT NULL default '0',
  `last_update` int(11) NOT NULL default '0',
  `mail` varchar(255) NOT NULL default '',
  `mail_enabled` enum('0','1') NOT NULL default '1',
  `mode` enum('0','1') NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO `phpshop_modules_guard_system` (`id`, `enabled`, `stop`, `used`, `chek_day_num`, `last_crc`, `last_chek`, `last_update`,`mail`, `mail_enabled`, `mode`) VALUES (1, '1', '0', '0', 1, 0, 0, 0, '', '1', '0');
