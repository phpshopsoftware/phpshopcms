DROP TABLE IF EXISTS `phpshop_modules_googletranslate_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_googletranslate_system` (
  `id` int(11) NOT NULL auto_increment,
  `lang` BLOB,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO `phpshop_modules_googletranslate_system` VALUES (1,'');