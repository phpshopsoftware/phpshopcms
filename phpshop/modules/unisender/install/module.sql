DROP TABLE IF EXISTS `phpshop_modules_unisender_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_unisender_system` (
  `id` int(11) auto_increment,
  `key` varchar(64)  default '',
  `version` FLOAT(2) DEFAULT '1.0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


INSERT INTO `phpshop_modules_unisender_system` VALUES (1,'','1.0');

ALTER TABLE `phpshop_send_mail` ADD `subscribe` enum('1','2') DEFAULT '1';