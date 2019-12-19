
-- 
-- Обновление таблицы `phpshop_orders`
-- 

ALTER TABLE `phpshop_pages` ADD `datas` varchar(64) NOT NULL default '';
ALTER TABLE `phpshop_pages` ADD `enabled` enum('0','1') NOT NULL default '1';
ALTER TABLE `phpshop_pages` ADD `title` varchar(255) NOT NULL default '';
ALTER TABLE `phpshop_pages` ADD `description` varchar(255) NOT NULL default '';
ALTER TABLE `phpshop_pages` ADD `skin_enabled` enum('0','1') NOT NULL default '0';
ALTER TABLE `phpshop_pages` ADD `skin` varchar(255) NOT NULL default '';


-- 
-- Обновление таблицы `phpshop_categories`
-- 
ALTER TABLE `phpshop_categories` ADD `PID` INT( 11 ) NOT NULL ;
ALTER TABLE `phpshop_categories` ADD `content` text NOT NULL;

-- 
-- Обновление таблицы `phpshop_system`
-- 
ALTER TABLE `phpshop_system` ADD `admoption` TEXT NOT NULL , ADD `rss_use` TINYINT( 1 ) NOT NULL ;


-- 
-- Обновление таблицы `phpshop_categories`
-- 
ALTER TABLE `phpshop_categories` ADD `PID` INT( 11 ) NOT NULL ;
ALTER TABLE `phpshop_categories` ADD `content` text NOT NULL;


-- 
-- Обновление таблицы `phpshop_system`
-- 
ALTER TABLE `phpshop_system` ADD `admoption` TEXT NOT NULL , ADD `rss_use` TINYINT( 1 ) NOT NULL ;


-- 
-- Структура таблицы `phpshop_photo_categories`
-- 

CREATE TABLE `phpshop_photo_categories` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `PID` int(11) default NULL,
  `link` varchar(64) NOT NULL,
  `name` varchar(64) NOT NULL default '',
  `num` tinyint(11) NOT NULL default '0',
  `content` text NOT NULL,
  `enabled` enum('0','1') NOT NULL,
  `page` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=cp1251 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

-- 
-- Структура таблицы `phpshop_photo_foto`
-- 

CREATE TABLE `phpshop_photo_foto` (
  `id` int(11) NOT NULL auto_increment,
  `PID` int(11) default '0',
  `enabled` enum('0','1') NOT NULL,
  `name` varchar(64) NOT NULL default '',
  `num` tinyint(11) NOT NULL default '0',
  `info` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `parent` (`PID`)
) ENGINE=MyISAM AUTO_INCREMENT=98 DEFAULT CHARSET=cp1251 AUTO_INCREMENT=98 ;

-- --------------------------------------------------------

-- 
-- Структура таблицы `phpshop_rssgraber`
-- 

CREATE TABLE `phpshop_rssgraber` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `link` text NOT NULL,
  `day_num` int(1) NOT NULL default '1',
  `news_num` mediumint(8) NOT NULL default '0',
  `enabled` enum('0','1') NOT NULL default '1',
  `start_date` int(16) unsigned NOT NULL default '0',
  `end_date` int(16) unsigned NOT NULL default '0',
  `last_load` int(16) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=cp1251 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

-- 
-- Структура таблицы `phpshop_rssgraber_jurnal`
-- 

CREATE TABLE `phpshop_rssgraber_jurnal` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `date` int(15) unsigned NOT NULL default '0',
  `link_id` int(11) NOT NULL default '0',
  `status` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=cp1251 AUTO_INCREMENT=3 ;


-- 
-- Структура таблицы `phpshop_modules`
-- 

CREATE TABLE `phpshop_modules` (
  `path` varchar(255) NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY  (`path`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


-- 
-- Обновление таблиц
--

ALTER TABLE `phpshop_users` CHANGE `enabled` `enabled` ENUM( '0', '1' ) DEFAULT '1' NOT NULL;
ALTER TABLE `phpshop_pages` CHANGE `enabled` `enabled` ENUM( '0', '1', '2' ) DEFAULT '1' NOT NULL; 
ALTER TABLE `phpshop_categories` CHANGE `PID` `parent_to` INT( 11 ) DEFAULT '0' NOT NULL;
ALTER TABLE `phpshop_pages` CHANGE `datas` `date` VARCHAR( 64 ) CHARACTER SET cp1251 COLLATE cp1251_general_ci NOT NULL;
ALTER TABLE `phpshop_pages` CHANGE `enabled` `enabled` ENUM( '0', '1', '2' ) DEFAULT '1' NOT NULL 
ALTER TABLE `phpshop_baners` RENAME `phpshop_banners`;
ALTER TABLE `phpshop_news` CHANGE `datas` `date` VARCHAR( 32 ) CHARACTER SET cp1251 COLLATE cp1251_general_ci NOT NULL;
ALTER TABLE `phpshop_news` CHANGE `zag` `title` TEXT CHARACTER SET cp1251 COLLATE cp1251_general_ci NOT NULL;
ALTER TABLE `phpshop_news` CHANGE `kratko` `description` TEXT CHARACTER SET cp1251 COLLATE cp1251_general_ci NOT NULL;
ALTER TABLE `phpshop_news` CHANGE `podrob` `content` TEXT CHARACTER SET cp1251 COLLATE cp1251_general_ci NOT NULL;
ALTER TABLE `phpshop_banners` CHANGE `datas` `date` VARCHAR( 32 ) CHARACTER SET cp1251 COLLATE cp1251_general_ci NOT NULL;
ALTER TABLE `phpshop_banners` CHANGE `flag` `enabled` ENUM( '0', '1' ) DEFAULT '0' NOT NULL;
ALTER TABLE `phpshop_photo_foto` RENAME `phpshop_photo` ;
ALTER TABLE `phpshop_photo_categories` CHANGE `PID` `parent_to` INT( 11 ) DEFAULT '0';
ALTER TABLE `phpshop_photo` CHANGE `PID` `category` INT( 11 ) DEFAULT '0';
ALTER TABLE `phpshop_system` CHANGE `adminmail2` `admin_mail` VARCHAR( 64 ) CHARACTER SET cp1251 COLLATE cp1251_general_ci NOT NULL;
ALTER TABLE `phpshop_gbook` CHANGE `datas` `date` INT( 11 ) DEFAULT NULL;
ALTER TABLE `phpshop_gbook` CHANGE `tema` `title` TEXT CHARACTER SET cp1251 COLLATE cp1251_general_ci DEFAULT NULL;
ALTER TABLE `phpshop_gbook` CHANGE `otsiv` `question` TEXT CHARACTER SET cp1251 COLLATE cp1251_general_ci DEFAULT NULL;
ALTER TABLE `phpshop_gbook` CHANGE `otvet` `answer` TEXT CHARACTER SET cp1251 COLLATE cp1251_general_ci DEFAULT NULL;
ALTER TABLE `phpshop_gbook` CHANGE `flag` `enabled` ENUM( '0', '1' ) DEFAULT '0' NOT NULL;
ALTER TABLE `phpshop_links` CHANGE `opis` `content` TEXT CHARACTER SET cp1251 COLLATE cp1251_general_ci NOT NULL;
ALTER TABLE `phpshop_send_mail` CHANGE `datas` `date` VARCHAR( 32 ) CHARACTER SET cp1251 COLLATE cp1251_general_ci NOT NULL;
ALTER TABLE `phpshop_system` CHANGE `spec_num` `skin_choice` ENUM( '0', '1' ) DEFAULT '0' NOT NULL;

-- 
-- Обновление таблиц
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
-- Структура таблицы `phpshop_modules_key`
--

CREATE TABLE IF NOT EXISTS `phpshop_modules_key` (
  `path` varchar(64) NOT NULL DEFAULT '',
  `date` int(11) DEFAULT '0',
  `key` text,
  `verification` varchar(32)  DEFAULT '',
  PRIMARY KEY (`path`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- Структура таблицы `phpshop_jurnal`
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
-- Структура таблицы `phpshop_black_list`
--

CREATE TABLE IF NOT EXISTS `phpshop_black_list` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(32) NOT NULL DEFAULT '',
  `datas` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- Структура таблицы `phpshop_newsletter`
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






