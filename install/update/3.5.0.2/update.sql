-- 
-- ���������� ������
--

ALTER TABLE  `phpshop_users` ADD  `hash` VARCHAR( 255 ) NOT NULL;
ALTER TABLE  `phpshop_system` ADD  `addres` varchar(255) NOT NULL;



CREATE TABLE IF NOT EXISTS `phpshop_slider` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `image` varchar(255) NOT NULL,
  `enabled` enum('0','1') NOT NULL DEFAULT '0',
  `num` smallint(6) NOT NULL,
  `link` varchar(255) NOT NULL,
  `alt` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;



--
-- ��������� ������� `phpshop_modules_key`
--

CREATE TABLE IF NOT EXISTS `phpshop_modules_key` (
  `path` varchar(64) NOT NULL DEFAULT '',
  `date` int(11) DEFAULT '0',
  `key` text,
  `verification` varchar(32)  DEFAULT '',
  PRIMARY KEY (`path`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- ��������� ������� `phpshop_jurnal`
--

CREATE TABLE IF NOT EXISTS `phpshop_jurnal` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user` varchar(64) NOT NULL DEFAULT '',
  `datas` varchar(32) NOT NULL DEFAULT '',
  `flag` enum('0','1') NOT NULL DEFAULT '0',
  `ip` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

--
-- ��������� ������� `phpshop_black_list`
--

CREATE TABLE IF NOT EXISTS `phpshop_black_list` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(32) NOT NULL DEFAULT '',
  `datas` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- ��������� ������� `phpshop_newsletter`
--

CREATE TABLE IF NOT EXISTS `phpshop_newsletter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `template` int(11) DEFAULT '0',
  `date` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


ALTER TABLE `phpshop_system` ADD `logo` VARCHAR(255) NOT NULL;
ALTER TABLE `phpshop_users` CHANGE `status` `status` BLOB NOT NULL;

