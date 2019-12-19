ALTER TABLE `phpshop_photo_categories` ADD `seoname` VARCHAR(255) NOT NULL;
ALTER TABLE `phpshop_photo_categories` ADD `seokey` VARCHAR(255) NOT NULL;
ALTER TABLE `phpshop_photo_categories` ADD `seotitle` VARCHAR(255) NOT NULL;
ALTER TABLE `phpshop_photo_categories` ADD `seodesc` VARCHAR(255) NOT NULL;
ALTER TABLE `phpshop_news` ADD `seo_name` VARCHAR(255) NOT NULL;
ALTER TABLE `phpshop_news` ADD `seo_key` VARCHAR(255) NOT NULL;
ALTER TABLE `phpshop_news` ADD `seo_title` VARCHAR(255) NOT NULL;
ALTER TABLE `phpshop_news` ADD `seo_desc` VARCHAR(255) NOT NULL;
ALTER TABLE `phpshop_categories` ADD `seoname` VARCHAR(255) NOT NULL;
ALTER TABLE `phpshop_categories` ADD `seotitle` VARCHAR(255) NOT NULL;
ALTER TABLE `phpshop_categories` ADD `seodesc` VARCHAR(255) NOT NULL;
ALTER TABLE `phpshop_categories` ADD `seokey` VARCHAR(255) NOT NULL;

--
-- Структура таблицы `phpshop_modules_stat_system`
--

DROP TABLE IF EXISTS `phpshop_modules_seourl_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_seourl_system` (
  `id` int(11) NOT NULL auto_increment,
  `serial` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;



INSERT INTO `phpshop_modules_seourl_system` VALUES (1,'');