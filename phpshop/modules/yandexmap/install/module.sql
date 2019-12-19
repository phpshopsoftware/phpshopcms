
--
-- Структура таблицы `phpshop_modules_skinpage_system`
--

DROP TABLE IF EXISTS `phpshop_modules_yandexmap_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_yandexmap_system` (
  `id` int(11) NOT NULL auto_increment,
  `code` text NOT NULL default '',
  `serial` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;



INSERT INTO `phpshop_modules_yandexmap_system` VALUES (1,'','');