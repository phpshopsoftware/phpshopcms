

DROP TABLE IF EXISTS `phpshop_modules_returncall_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_returncall_system` (
  `id` int(11) NOT NULL auto_increment,
  `enabled` enum('0','1','2') NOT NULL default '1',
  `title` varchar(64) NOT NULL default '',
  `title_end` text NOT NULL,
  `serial` varchar(64) NOT NULL default '',
  `windows` enum('0','1') NOT NULL default '0',
  `captcha_enabled` enum('1','2') NOT NULL default '1',
  `version` FLOAT(2) DEFAULT '1.4' NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


INSERT INTO `phpshop_modules_returncall_system` VALUES (1,'1','Обратный звонок','Спасибо! Мы скоро свяжемся с Вами.','','0','1','1.4');

DROP TABLE IF EXISTS `phpshop_modules_returncall_jurnal`;
CREATE TABLE `phpshop_modules_returncall_jurnal` (
  `id` int(11) NOT NULL auto_increment,
  `date` int(11) NOT NULL default '0',
  `time_start` FLOAT DEFAULT '10.00' NOT NULL,
  `time_end` FLOAT DEFAULT '18.00' NOT NULL,
  `name` varchar(64) NOT NULL default '',
  `tel` varchar(64) NOT NULL default '',
  `message` text NOT NULL,
  `status` enum('1','2','3','4') NOT NULL default '1',
  `ip` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;