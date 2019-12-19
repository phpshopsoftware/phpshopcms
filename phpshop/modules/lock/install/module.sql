DROP TABLE IF EXISTS `phpshop_modules_lock_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_lock_system` (
  `id` int(11) auto_increment,
  `flag` enum('1','2') default '1',
  `login` varchar(64)  default 'admin',
  `password` varchar(64) default 'admin',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;



INSERT INTO `phpshop_modules_lock_system` VALUES (1,'1','admin','admin');