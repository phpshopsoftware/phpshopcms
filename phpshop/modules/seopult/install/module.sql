

DROP TABLE IF EXISTS `phpshop_modules_seopult_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_seopult_system` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(64) NOT NULL DEFAULT '',
  `login` varchar(64) NOT NULL DEFAULT '',
  `email` varchar(64) NOT NULL,
  `hash` varchar(64) NOT NULL,
  `cryptkey` varchar(255) NOT NULL,
  `serial` varchar(64) NOT NULL DEFAULT '',
  `version` float NOT NULL DEFAULT '1.1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;


INSERT INTO `phpshop_modules_seopult_system` (`id`, `url`, `login`, `email`, `hash`, `cryptkey`, `serial`, `version`) VALUES
(1, '', '', '', '', '', '', '1.1');

