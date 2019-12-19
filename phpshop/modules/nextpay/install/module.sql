

DROP TABLE IF EXISTS `phpshop_modules_nextpay_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_nextpay_system` (
  `id` int(11) NOT NULL auto_increment,
  `status` int(11) NOT NULL,
  `title` text NOT NULL,
  `title_sub` text NOT NULL,
  `link_top_text` text NOT NULL,
  `link_text` text NOT NULL,
  `merchant_key` varchar(64) NOT NULL default '',
  `merchant_key2` varchar(64) NOT NULL default '',
  `merchant_skey` varchar(64) NOT NULL default '',
  `version` FLOAT(2) DEFAULT '1.0' NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


INSERT INTO `phpshop_modules_nextpay_system` VALUES (1,0,'Оплатите пожалуйста свой заказ','Заказ находится на ручной проверке.','Ваш заказ оплачен','Ваш заказ успешно оплачен','','','','1.0');

INSERT INTO `phpshop_payment_systems` VALUES (10016, 'Visa, Mastercard (NextPay)', 'modules', '0', 0, '', '', '', '/UserFiles/Image/Payments/visa.png');