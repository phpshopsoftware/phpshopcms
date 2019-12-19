
ALTER TABLE `phpshop_pages` ADD `name_locale` TEXT NOT NULL;
ALTER TABLE `phpshop_pages` ADD `content_locale` TEXT NOT NULL;

ALTER TABLE `phpshop_categories` ADD `name_cat_locale` TEXT NOT NULL;
ALTER TABLE `phpshop_categories` ADD `content_cat_locale` TEXT NOT NULL;

ALTER TABLE `phpshop_menu` ADD `name_menu_locale` TEXT NOT NULL;
ALTER TABLE `phpshop_menu` ADD `content_menu_locale` TEXT NOT NULL;

ALTER TABLE `phpshop_news` ADD `title_news_locale` TEXT NOT NULL;
ALTER TABLE `phpshop_news` ADD `description_news_locale` TEXT NOT NULL;
ALTER TABLE `phpshop_news` ADD `content_news_locale` TEXT NOT NULL;


--
-- Структура таблицы `phpshop_modules_stat_system`
--

DROP TABLE IF EXISTS `phpshop_modules_locale_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_locale_system` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `skin` varchar(64) NOT NULL default '',
  `skin_enabled` enum('0','1') NOT NULL default '0',
  `serial` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;



INSERT INTO `phpshop_modules_locale_system` VALUES (1,'My site','','0','');