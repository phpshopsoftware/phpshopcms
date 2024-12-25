
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
  `flag` enum('0','1') DEFAULT '0',
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
-- ��������� ������� `phpshop_slider`
--

CREATE TABLE IF NOT EXISTS `phpshop_slider` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `image` varchar(255) NOT NULL,
  `enabled` enum('0','1') DEFAULT '0',
  `num` smallint(6),
  `link` varchar(255),
  `alt` varchar(255),
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

--
-- ���� ������ ������� `phpshop_slider`
--

INSERT INTO `phpshop_slider` (`id`, `image`, `enabled`, `num`, `link`, `alt`) VALUES
(1, '/UserFiles/Image/demo/slider/slider2.png', '1', 0, '', ''),
(2, '/UserFiles/Image/demo/slider/slider1.png', '1', 0, '', '');

-- 
-- ��������� ������� `phpshop_modules`
-- 

CREATE TABLE `phpshop_modules` (
  `path` varchar(255) default '',
  `name` varchar(255) default '',
  `date` int(11) default '0',
  PRIMARY KEY  (`path`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- ���� ������ ������� `phpshop_modules`
--

INSERT INTO `phpshop_modules` (`path`, `name`, `date`) VALUES
('button', 'Button', 1408525705);


-- ��������� ������� `phpshop_modules_button_forms`
--

CREATE TABLE IF NOT EXISTS `phpshop_modules_button_forms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `enabled` enum('0','1') NOT NULL DEFAULT '1',
  `num` tinyint(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

--
-- ���� ������ ������� `phpshop_modules_button_forms`
--

INSERT INTO `phpshop_modules_button_forms` (`id`, `name`, `content`, `enabled`, `num`) VALUES
(1, '������� �������', '<!-- �������� ��� �������� ���� --><img src="/UserFiles/Image/demo/cycounter.gif">\r\n<!-- �������� ��� �������� ���� -->\r\n<img src="/UserFiles/Image/demo/metrika.png">\r\n', '1', 1);


--
-- ��������� ������� `phpshop_modules_button_system`
--

CREATE TABLE IF NOT EXISTS `phpshop_modules_button_system` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enabled` enum('0','1','2','3') NOT NULL DEFAULT '1',
  `serial` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

--
-- ���� ������ ������� `phpshop_modules_button_system`
--

INSERT INTO `phpshop_modules_button_system` (`id`, `enabled`, `serial`) VALUES
(1, '0', '');

-- 
-- ��������� ������� `phpshop_banners`
-- 

CREATE TABLE `phpshop_banners` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(64) default '',
  `content` text NOT NULL,
  `count_all` int(64) default '0',
  `count_today` int(64) default '0',
  `enabled` enum('0','1') default '0',
  `date` varchar(32) default '',
  `limit_all` int(32) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


--
-- ���� ������ ������� `phpshop_banners`
--

INSERT INTO `phpshop_banners` (`id`, `name`, `content`, `enabled` ) VALUES
(1, '��� ������', '<img src="/UserFiles/Image/demo/slider/phpshop_banner.png" width="100%">', '1');

-- 
-- ��������� ������� `phpshop_categories`
-- 


CREATE TABLE `phpshop_categories` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) default '',
  `num` int(64) default '1',
  `parent_to` int(11) default '0',
  `content` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- ���� ������ ������� `phpshop_categories`
--

INSERT INTO `phpshop_categories` VALUES (3, '� PHPShop.CMS Free', 1, 0, '');


-- --------------------------------------------------------

-- 
-- ��������� ������� `phpshop_gbook`
-- 

CREATE TABLE `phpshop_gbook` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `date` int(11) default '0',
  `name` varchar(32) default '',
  `mail` varchar(32) default '',
  `title` text,
  `question` text,
  `answer` text ,
  `enabled` enum('0','1') default '0',
  PRIMARY KEY  (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- ���� ������ ������� `phpshop_gbook`
--

INSERT INTO `phpshop_gbook` VALUES (1, 1295427600, '���-������', '', '���� ������� ��������� ����������?', '� ���� ���� ��������� ������������ ��� ����� ������������ ���� ���������?', '������� PHPShop CMS Free - �������� ���������� ��������� ���������� ������. �� ������ ������������ �� � ����� �������� ���������� ��������� �������� <a href="/doc/license.html">������������� ����������</a>.', '1');
       
-- --------------------------------------------------------

--
-- ��������� ������� `phpshop_links`
--

CREATE TABLE `phpshop_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT '',
  `image` text,
  `content` text ,
  `link` text,
  `num` int(11) DEFAULT 1,
  `enabled` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

--
-- ���� ������ ������� `phpshop_links`
--

INSERT INTO `phpshop_links` (`id`, `name`, `image`, `content`, `link`, `num`, `enabled`) VALUES
(1, 'PHPShop Software', '', '�������� ��������-��������, ������ ��������-�������� PHPShop.', 'http://www.phpshop.ru', 5, '1'),
(2, 'PHPShop CMS Free', '', '���������� ��c���� ���������� ������ PHPShop CMS Free.', 'http://www.phpshopcmsfree.ru', 3, '1'),
(3, '������ ��������-��������', '', '������ ������ ��������-�������� Shopbuilder ���������� ������������� ������ ������� ����������� ��������-�������', 'http://www.shopbuilder.ru', 1, '1');


-- --------------------------------------------------------

-- 
-- ��������� ������� `phpshop_menu`
-- 


CREATE TABLE `phpshop_menu` (
  `id` int(32) NOT NULL auto_increment,
  `name` varchar(32) default '',
  `content` text,
  `flag` varchar(64) default '1',
  `num` int(16) default '0',
  `dir` text,
  `element` tinyint(1) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- ���� ������ ������� `phpshop_menu`
--

INSERT INTO `phpshop_menu` (`id`, `name`, `content`, `flag`, `num`, `dir`, `element`) VALUES
(1, '�������', '<p><a href="http://www.phpshop.ru" target="_blank" title="���� ������������"><img title="PHPShop " src="/UserFiles/Image/demo/phpshop.png" alt="PHPShop 5" class="img-thumbnail"></a></p><h4>PHPShop 6</h4><p class="media-body">������� ������ <a href="http�://www.phpshop.ru" target="_blank">PHPShop 6</a> � ���������� ������������ "���� ��������" ��������� ����� 100 ����� �������.</p>', '1', 1, '', 1);


-- --------------------------------------------------------

-- 
-- ��������� ������� `phpshop_news`
-- 

CREATE TABLE `phpshop_news` (
  `id` int(64) NOT NULL auto_increment,
  `date` varchar(32) default '',
  `title` text,
  `description` text,
  `content` text,
  PRIMARY KEY  (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


-- --------------------------------------------------------

-- 
-- ��������� ������� `phpshop_opros`
-- 

CREATE TABLE `phpshop_opros` (
  `id` int(11) NOT NULL auto_increment,
  `category` int(11) unsigned default '0',
  `name` varchar(255) default '',
  `total` int(11) default '0',
  `num` tinyint(32)  default '0',
  PRIMARY KEY  (`id`),
  KEY `category` (`category`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;



-- --------------------------------------------------------

-- 
-- ��������� ������� `phpshop_opros_categories`
-- 

CREATE TABLE `phpshop_opros_categories` (
  `id` int(11) NOT NULL auto_increment,
  `name` text,
  `dir` varchar(32) default '',
  `flag` enum('0','1')  default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


-- --------------------------------------------------------

-- 
-- ��������� ������� `phpshop_pages`
-- 


CREATE TABLE `phpshop_pages` (
  `id` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  `link` varchar(64) default '',
  `category` int(32) default '0',
  `keywords` text,
  `content` text,
  `flag` varchar(16) default '1',
  `num` smallint(3) default '0',
  `date` varchar(64) default '',
  `enabled` enum('0','1','2') default '1',
  `title` varchar(255) default '',
  `description` varchar(255) default '',
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `link` (`link`),
  FULLTEXT KEY `content` (`content`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- ���� ������ ������� `phpshop_pages`
--

INSERT INTO `phpshop_pages` (`id`, `name`, `link`, `category`, `keywords`, `content`, `flag`, `num`, `date`, `enabled`, `title`, `description`) VALUES
(1, '���������� ��� �� ��������� PHPShop  @version@', 'index', 2000, '', '<p><img alt="PHPShop" src="/UserFiles/Image/demo/box.png" style="float: left; margin: 0px 10px 10px 0px;">PHPShop - ����������� ����������� ���-������, � ������� ��������� ����������� ���-���������� � ��� 20-������ ���� ������. ������� PHPShop - ��� PHP, MySQL, HTML5, Bootstrap, jQuery, ����������� ��������� ������������, �������������� ������� � ����������� � ���������� �������� ��� ��������� ������, ������� �� �������� ���-����� ��������, � ���������� ��������-��������� � ������������ ��� 1�/��������, ����������� ��������������, ���������� ��������� � �������� ��������.</p>', '', 0, '1193224168', '1', '', ''),
(2, '��� ����� PHPShop CMS', 'freecms', 3, 'PHPShop CMS, �����-�����, �����������', '<p><strong>PHPShop CMS Free</strong>  ���������� ������ ������, ����������� ������ ������������ �������� �������������� � ��� �������� ������� � ������������ ���� ����� ���������. � ����� ������������ ��������� ������� ��������� ��� �������� ����� � ����� �������, � ������� ������� ����� ��������� ���������� ����� ��� ���� �����.\n</p>\n<p><strong>������� ���������� ������ PHPShop CMS Free � ��� ������������� ��������� ���������������� ���������, ������� ������� �������� �� �������������.</strong>\n</p>\n<p><strong> </strong>C ������� <strong>PHPShop CMS Free</strong> �� ������� �������������� ������� ���� �� ���� �����. �� ��� �� ����������� �����-���� ������������� ������, ���� �� ������ ����� �� ��� ��������, �� ������� ���� �� ��������� PHPShop CMS Free ��� ����� �� �������� �����.\n</p>\n<h2>������ ��� �������� PHPShop CMS Free?</h2>\n<p>��� <strong>������� ��������� ��� �������� ����� ����� ���������</strong>. ���� �� ������ ������� ������������ �������� � ���������, ������� ����-������� ��� ����� �������� ��� ���� ���������� � ��� ����-�������, �� ��� ���� �� ������ ������� ������ �� ������ ���-�������������, PHPShop CMS Free - ��� ��, ��� ��� �����.\n</p>\n<p>���� ��� ����, ����� ������� ���� ���������� ����� ���� ������� ������� ���������, ���������� �� �� ���� ���������, ��������� �� ������ ����� � ��������� �� �������. ��������, �� ������� � ���� ���������.\n</p>\n<h2>��������� ����� ������� PHPShop CMS Free?</h2>\n<p>����� ��������� CMS �� ���� ���������, � ������ �������, �� ������� �� <strong>�����������, ���������� �������� ���������</strong>. ��� ���� ����� ��� ���� ����� ��������� � ����� ���������, ������� �� ��� ��������� � ���������� ����� Windows.\n</p>\n<p>�� �������� ������ �������� ������ �������� ������� �������� ����� ������������ �������. ���� �� �� ������� � ����������� ��������, ��� ������������ � �������� �� ����� ������������ ����������� ������������ PHPShop CMS Free, � ������� �������� ����������� ��� �������� �������� � ��������.\n</p>\n<p>�� ����������� ������ ��� �������� �� phpshopcmsfree.ru, ����� ����������� � ��� ��� ���� ������� - ��� ��������� PHPShop Easy Control, �� �������������� �� ���� ��������� � ������� ����. ����� ����, �� ������ ������ ���������� �� ������� � ������������� ������, �� ����� �����, ��� �������� ����� �� ������ � ������� F.A.Q - ����� ���������� �������.\n</p>\n<h2>��� ��������� ������ �����?</h2>\n<p>��� �� ����������� �������� ���-���������, ���������� ������� <strong>����� ������� ������ ������� �� ����� ��� 100 ����������� � ����� ����</strong>. ��� ����� ������� ������������� ������������ HTML-��������, ����������� ������������� � ����� ���� ������� ������� � ������������� ��� ������� ����� �� �����������.\n</p>\n<h2>��� ��������� ���� ��������� (����������)?</h2>\n<p>����� ������, ��� CMS ����� ������� �� ���������������� � ���������������� ������, ���� �� ��� ����� ������������, � ������ - ��. ����������� ��������� ������������ � ���������������� �����, � ����� ���������� ������������ � ����������������.\n</p>\n<p>���������� ����� � ���������������� ����� ������������ � ���� ������������ �������� �������, ������������ ����� �������� ����� ����������� ���������� �� ������������ �������������� (���, ������, ���������) ��� �� ����������� ������ ��������. �� ������ ��������� ����� �������� � ������� ��� ������ �� �������, � �����, ��� �������, ��������� ���� ������-������ ��� ������ �� ���.\n</p>\n<p>��� ���������� ������ � �������� �� �������� �����, � ���������������� ����� CMS ����������� ������� <strong>WYSIWYG �������� ��������</strong>. �� �������� � Word �� ������ ������ � ���� ����� ���������, ��������, � ��� �� ��������� �������� ����� ��� ��������, �� ������� ��� ���� ����� ����� ����� ����������� (��������� �����������). �� ������ �������� �� ������� ��������� �� ���� ����������� ������� � ������, � ����� ������������ �������� � ������ ��� ������������� �����.\n</p>\n<h2>����� �������� � �������?</h2>\n<p>PHPShop CMS Free - <strong>��������� ������ ���������� ��������-������������ PHPShop Software</strong>. �� ������ ������������ CMS ��� ������ � ��������-��������� � ����� ���������� �� �������� � ������������ ������ ����� ���������.\n</p>\n<p>� 2016 ���� ���������� ������������� ���������� ������� ���������� ������ PHPShop CMS Free ���������� �� 50 000. �� ����� ��������, ��� <strong>���������� ��������� ����� ���� �� ���� �������������</strong>, � ���������� ��� ����� � ���� ���������.\n</p>', '1', 0, '', '1', '', ''),
(9, '�����������������', 'admin', 1000, '', '<p>��� ������� � ������ ���������� PHPShop ������� ��������� ������ <kbd>Ctrl &#43; F12</kbd> ��� ����������� ������ �������� ����.<br> \n����� �� ��������� \n	<strong>demo</strong>, ������ <strong>demouser</strong>. <br> \n���� �� ��� ��������� ������ ���� ����� � ������, �� ����������� ���� ������ ��� �����������.\n</p>\n<p>\n	<input value="������� � ������ ����������" onclick="window.location.replace(''..phpshop/admpanel/'');" type="button">\n</p>\n<h2>�������� ����</h2>\n��� ��������� �������� ����������� �������� �������� ���� ��� ������������ ������������ ���������. ��� ������� �������� ���� ������� � ������ ���������� ��������� ������� � ���� <kbd>����</kbd> - <kbd>SQL ������ � ����</kbd> � ������� � ���������� ������ ����� <strong>"�������� ����"</strong>. �������� ���� ��������, ��� ��������� ���  ���� � ������� ������ ������ �����.', '1', 0, '1400508757', '1', '����������������� PHPShop', '');


-- --------------------------------------------------------

-- 
-- ��������� ������� `phpshop_photo_categories`
-- 

CREATE TABLE `phpshop_photo_categories` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `parent_to` int(11) default '0',
  `link` varchar(64) default '',
  `name` varchar(64) default '',
  `num` tinyint(11) default '0',
  `content` text,
  `enabled` enum('0','1') default '0',
  `page` varchar(255)  default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;



-- --------------------------------------------------------

-- 
-- ��������� ������� `phpshop_photo`
-- 


CREATE TABLE `phpshop_photo` (
  `id` int(11) NOT NULL auto_increment,
  `category` int(11) default '0',
  `enabled` enum('0','1') default '0',
  `name` varchar(64) default '',
  `num` tinyint(11)  default '0',
  `info` varchar(255) default '',
  PRIMARY KEY  (`id`),
  KEY `parent` (`category`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


-- --------------------------------------------------------

-- 
-- ��������� ������� `phpshop_rssgraber`
-- 

CREATE TABLE `phpshop_rssgraber` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `link` text NOT NULL,
  `day_num` int(1) default '1',
  `news_num` mediumint(8) default '0',
  `enabled` enum('0','1')  default '1',
  `start_date` int(16) unsigned  default '0',
  `end_date` int(16) unsigned default '0',
  `last_load` int(16) unsigned default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


-- 
-- ���� ������ ������� `phpshop_rssgraber`
-- 

INSERT INTO `phpshop_rssgraber` (`id`, `link`, `day_num`, `news_num`, `enabled`, `start_date`, `end_date`, `last_load`) VALUES
(1, 'http://www.phpshop.ru/rss/', 1, 3, '1', 1257714000, 1953700800, 1200616000);


-- --------------------------------------------------------

-- 
-- ��������� ������� `phpshop_rssgraber_jurnal`
-- 

CREATE TABLE `phpshop_rssgraber_jurnal` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `date` int(15) unsigned  default '0',
  `link_id` int(11) default '0',
  `status` enum('0','1')  default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;



-- --------------------------------------------------------

-- 
-- ��������� ������� `phpshop_send_mail`
-- 

CREATE TABLE `phpshop_send_mail` (
  `id` int(64) NOT NULL auto_increment,
  `date` varchar(32) default '',
  `mail` varchar(32) default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- ��������� ������� `phpshop_system`
--

CREATE TABLE IF NOT EXISTS `phpshop_system` (
  `id` tinyint(1) NOT NULL AUTO_INCREMENT,
  `name` text,
  `company` text,
  `num_row` int(10) DEFAULT NULL,
  `skin` varchar(32) DEFAULT NULL,
  `admin_mail` varchar(64) DEFAULT '',
  `title` text,
  `keywords` text,
  `skin_choice` enum('0','1') DEFAULT '0',
  `tel` text,
  `meta` text,
  `admoption` text,
  `rss_use` tinyint(1)  DEFAULT '0',
  `addres` varchar(255),
  `logo` varchar(255),
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

--
-- ���� ������ ������� `phpshop_system`
--

INSERT INTO `phpshop_system` (`id`, `name`, `company`, `num_row`, `skin`, `admin_mail`, `title`, `keywords`, `skin_choice`, `tel`, `meta`, `admoption`, `rss_use`, `addres`, `logo`) VALUES
(1, '���������� ������', 'PHPShop Software', 5, 'diggi', 'admin@localhost', 'PHPShop CMS Free 5  - ���������� ������', '���������� ������, phpshop', '1', '(495) 111-222-333', '�������� ������������ PHPShop CMS', 'a:40:{s:5:"theme";s:7:"default";s:6:"editor";s:7:"default";s:9:"ace_theme";s:4:"dawn";s:9:"adm_title";s:0:"";s:18:"rss_graber_enabled";s:1:"1";s:9:"user_skin";i:0;s:20:"templateshop_enabled";i:0;s:5:"img_w";s:3:"500";s:5:"img_h";s:3:"500";s:14:"width_podrobno";s:3:"100";s:17:"image_result_path";s:0:"";s:6:"img_tw";s:3:"150";s:6:"img_th";s:3:"150";s:12:"width_kratko";s:3:"100";s:14:"watermark_text";s:0:"";s:20:"watermark_text_color";s:7:"#cccccc";s:19:"watermark_text_size";s:2:"20";s:19:"watermark_text_font";s:9:"Astronaut";s:15:"watermark_right";s:2:"10";s:16:"watermark_bottom";s:2:"10";s:20:"watermark_text_alpha";s:2:"80";s:15:"watermark_image";s:0:"";s:17:"image_save_source";i:0;s:21:"image_adaptive_resize";i:0;s:15:"image_save_name";i:0;s:21:"watermark_big_enabled";i:0;s:24:"watermark_source_enabled";i:0;s:14:"mail_smtp_host";s:0:"";s:14:"mail_smtp_port";s:0:"";s:14:"mail_smtp_user";s:0:"";s:14:"mail_smtp_pass";s:0:"";s:17:"mail_smtp_replyto";s:0:"";s:17:"recaptcha_enabled";s:1:"1";s:14:"recaptcha_pkey";s:0:"";s:14:"recaptcha_skey";s:0:"";s:14:"dadata_enabled";s:1:"1";s:12:"dadata_token";s:0:"";s:17:"mail_smtp_enabled";i:0;s:15:"mail_smtp_debug";i:0;s:14:"mail_smtp_auth";i:0;}', 0, '������, ��. ����������, ��� 1.', '/UserFiles/Image/demo/your_logo.png');



-- 
-- ��������� ������� `phpshop_users`
-- 

CREATE TABLE `phpshop_users` (
  `id` int(11) NOT NULL auto_increment,
  `login` varchar(64) default '',
  `password` varchar(64)  default '',
  `mail` varchar(64) default '',
  `enabled` enum('0','1') default '1',
  `hash` varchar(255),
  `status` blob NOT NULL,
  PRIMARY KEY  (`id`)
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
