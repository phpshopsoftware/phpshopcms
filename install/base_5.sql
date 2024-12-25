
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
  `flag` enum('0','1') DEFAULT '0',
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
-- Структура таблицы `phpshop_slider`
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
-- Дамп данных таблицы `phpshop_slider`
--

INSERT INTO `phpshop_slider` (`id`, `image`, `enabled`, `num`, `link`, `alt`) VALUES
(1, '/UserFiles/Image/demo/slider/slider2.png', '1', 0, '', ''),
(2, '/UserFiles/Image/demo/slider/slider1.png', '1', 0, '', '');

-- 
-- Структура таблицы `phpshop_modules`
-- 

CREATE TABLE `phpshop_modules` (
  `path` varchar(255) default '',
  `name` varchar(255) default '',
  `date` int(11) default '0',
  PRIMARY KEY  (`path`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `phpshop_modules`
--

INSERT INTO `phpshop_modules` (`path`, `name`, `date`) VALUES
('button', 'Button', 1408525705);


-- Структура таблицы `phpshop_modules_button_forms`
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
-- Дамп данных таблицы `phpshop_modules_button_forms`
--

INSERT INTO `phpshop_modules_button_forms` (`id`, `name`, `content`, `enabled`, `num`) VALUES
(1, 'Счетчик Яндекса', '<!-- Вставьте код счетчика сюда --><img src="/UserFiles/Image/demo/cycounter.gif">\r\n<!-- Вставьте код счетчика сюда -->\r\n<img src="/UserFiles/Image/demo/metrika.png">\r\n', '1', 1);


--
-- Структура таблицы `phpshop_modules_button_system`
--

CREATE TABLE IF NOT EXISTS `phpshop_modules_button_system` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enabled` enum('0','1','2','3') NOT NULL DEFAULT '1',
  `serial` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `phpshop_modules_button_system`
--

INSERT INTO `phpshop_modules_button_system` (`id`, `enabled`, `serial`) VALUES
(1, '0', '');

-- 
-- Структура таблицы `phpshop_banners`
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
-- Дамп данных таблицы `phpshop_banners`
--

INSERT INTO `phpshop_banners` (`id`, `name`, `content`, `enabled` ) VALUES
(1, 'Мой Баннер', '<img src="/UserFiles/Image/demo/slider/phpshop_banner.png" width="100%">', '1');

-- 
-- Структура таблицы `phpshop_categories`
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
-- Дамп данных таблицы `phpshop_categories`
--

INSERT INTO `phpshop_categories` VALUES (3, 'О PHPShop.CMS Free', 1, 0, '');


-- --------------------------------------------------------

-- 
-- Структура таблицы `phpshop_gbook`
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
-- Дамп данных таблицы `phpshop_gbook`
--

INSERT INTO `phpshop_gbook` VALUES (1, 1295427600, 'Веб-мастер', '', 'Ваша система полностью бесплатная?', 'Я могу вашу платформу использовать для своих коммерческих нужд бесплатно?', 'Система PHPShop CMS Free - открытая бесплатная платформа управления сайтом. Вы можете использовать ее в своих проектах совершенно бесплатно согласно <a href="/doc/license.html">лицензионному соглашению</a>.', '1');
       
-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_links`
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
-- Дамп данных таблицы `phpshop_links`
--

INSERT INTO `phpshop_links` (`id`, `name`, `image`, `content`, `link`, `num`, `enabled`) VALUES
(1, 'PHPShop Software', '', 'Создание интернет-магазина, скрипт интернет-магазина PHPShop.', 'http://www.phpshop.ru', 5, '1'),
(2, 'PHPShop CMS Free', '', 'Бесплатная сиcтема управления сайтом PHPShop CMS Free.', 'http://www.phpshopcmsfree.ru', 3, '1'),
(3, 'Аренда интернет-магазина', '', 'Сервис аренды интернет-магазина Shopbuilder предлагает пользователям быстро создать полноценный интернет-магазин', 'http://www.shopbuilder.ru', 1, '1');


-- --------------------------------------------------------

-- 
-- Структура таблицы `phpshop_menu`
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
-- Дамп данных таблицы `phpshop_menu`
--

INSERT INTO `phpshop_menu` (`id`, `name`, `content`, `flag`, `num`, `dir`, `element`) VALUES
(1, 'Новинка', '<p><a href="http://www.phpshop.ru" target="_blank" title="Сайт разработчика"><img title="PHPShop " src="/UserFiles/Image/demo/phpshop.png" alt="PHPShop 5" class="img-thumbnail"></a></p><h4>PHPShop 6</h4><p class="media-body">Платная версия <a href="httpы://www.phpshop.ru" target="_blank">PHPShop 6</a> с поддержкой конфигурации "Сайт компании" добавляет более 100 новых функций.</p>', '1', 1, '', 1);


-- --------------------------------------------------------

-- 
-- Структура таблицы `phpshop_news`
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
-- Структура таблицы `phpshop_opros`
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
-- Структура таблицы `phpshop_opros_categories`
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
-- Структура таблицы `phpshop_pages`
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
-- Дамп данных таблицы `phpshop_pages`
--

INSERT INTO `phpshop_pages` (`id`, `name`, `link`, `category`, `keywords`, `content`, `flag`, `num`, `date`, `enabled`, `title`, `description`) VALUES
(1, 'Благодарим вас за установку PHPShop  @version@', 'index', 2000, '', '<p><img alt="PHPShop" src="/UserFiles/Image/demo/box.png" style="float: left; margin: 0px 10px 10px 0px;">PHPShop - современный конструктор веб-сайтов, в котором соединены современные веб-технологии и наш 20-летний опыт работы. Начинка PHPShop - это PHP, MySQL, HTML5, Bootstrap, jQuery, позволяющие создавать качественные, функциональные проекты с современным и адаптивным дизайном под различные задачи, начиная от простого веб-сайта компании, и заканчивая интернет-магазином с интеграциями под 1С/МойСклад, популярными маркетплейсами, платежными системами и службами доставки.</p>', '', 0, '1193224168', '1', '', ''),
(2, 'Что такое PHPShop CMS', 'freecms', 3, 'PHPShop CMS, Видео-уроки, Презентация', '<p><strong>PHPShop CMS Free</strong>  уникальный проект Рунета, позволяющий любому пользователю Интернет самостоятельно и без вложений создать и поддерживать сайт любой сложности. В Вашем распоряжении находится готовая платформа для создания сайта и набор модулей, с помощью которых можно настроить функционал сайта под свои нужды.\n</p>\n<p><strong>Система управления сайтом PHPShop CMS Free и все сопутствующие программы распространяются бесплатно, никаких скрытых платежей не предусмотрено.</strong>\n</p>\n<p><strong> </strong>C помощью <strong>PHPShop CMS Free</strong> Вы сможете самостоятельно создать сайт за пару часов. От Вас не потребуется каких-либо специфических знаний, если Вы смогли зайти на эту страницу, то создать сайт на платформе PHPShop CMS Free тем более не составит труда.\n</p>\n<h2>Почему мне подходит PHPShop CMS Free?</h2>\n<p>Это <strong>готовая платформа для создания сайта любой сложности</strong>. Если Вы хотите создать персональную страницу в Интернете, сделать сайт-визитку для своей компании или даже разместить в нем мини-магазин, но при этом не хотите тратить деньги на услуги веб-разработчиков, PHPShop CMS Free - это то, что Вам нужно.\n</p>\n<p>Ведь для того, чтобы создать сайт достаточно всего лишь скачать готовую платформу, установить ее на свой компьютер, настроить по своему вкусу и выгрузить на хостинг. Поверьте, Вы сумеете с этим справится.\n</p>\n<h2>Насколько легко освоить PHPShop CMS Free?</h2>\n<p>После установки CMS на свой компьютер, в первую очередь, Вы оцените ее <strong>дружелюбный, интуитивно понятный интерфейс</strong>. Для того чтобы Вам было проще освоиться в новой программе, внешний ее вид приближен к привычному стилю Windows.\n</p>\n<p>На переднюю панель выведены иконки быстрого запуска наиболее часто используемых функций. Если вы не уверены в собственной интуиции, для ознакомления и обучения на сайте присутствует Руководство Пользователя PHPShop CMS Free, в котором пошагово объясняются все основные операции с системой.\n</p>\n<p>Не обязательно каждый раз заходить на phpshopcmsfree.ru, чтобы разобраться в том или ином вопросе - при установке PHPShop Easy Control, Вы устанавливаете на свой компьютер и учебник тоже. Кроме того, Вы всегда можете обратиться за помощью к пользователям форума, на нашем сайте, или поискать ответ на вопрос в разделе F.A.Q - часто задаваемые вопросы.\n</p>\n<h2>Как настроить дизайн сайта?</h2>\n<p>Вам не обязательно нанимать веб-дизайнера, попробуйте выбрать <strong>любой готовый шаблон дизайна из более чем 100 размещенных в нашей базе</strong>. Для более опытных пользователей предусмотрен HTML-редактор, позволяющий переключиться в режим кода шаблона дизайна и редактировать код каждого блока по отдельности.\n</p>\n<h2>Как наполнить сайт контентом (содержимым)?</h2>\n<p>Нужно понять, что CMS сайта состоит из пользовательской и административной частей, одну из них видят пользователи, а другую - Вы. Необходимые изменения производятся в административной части, и после сохранения отображаются в пользовательской.\n</p>\n<p>Содержимое сайта в административной части представлено в виде древовидного каталога страниц, перемещаться между которыми можно посредством сортировки по определенной характеристике (имя, ссылка, заголовок) или по порядковому номеру страницы. Вы можете добавлять новые страницы в каталог или менять их позиции, а также, при желании, назначить свой дизайн-шаблон для каждой из них.\n</p>\n<p>Для добавления текста и картинок на страницы сайта, в административной части CMS представлен удобный <strong>WYSIWYG редактор контента</strong>. По аналогии с Word вы можете ввести в поле текст заголовка, описания, а так же прописать ключевые слова для страницы, по которым Ваш сайт можно будет найти поисковиком (поисковая оптимизация). По такому принципу Вы сможете добавлять на сайт собственные новости и статьи, а также сформировать рассылки и опросы для пользователей сайта.\n</p>\n<h2>Какие гарантии я получаю?</h2>\n<p>PHPShop CMS Free - <strong>имиджевый проект российской компании-разработчика PHPShop Software</strong>. Мы делаем качественные CMS для сайтов и интернет-магазинов и можем поручиться за быстроту и стабильность работы наших продуктов.\n</p>\n<p>В 2016 году количество пользователей бесплатной системы управления сайтом PHPShop CMS Free перевалило за 50 000. Мы хотим показать, что <strong>бесплатные программы могут быть не хуже дорогостоящих</strong>, и предлагаем Вам самим в этом убедиться.\n</p>', '1', 0, '', '1', '', ''),
(9, 'Администрирование', 'admin', 1000, '', '<p>Для доступа к панели управления PHPShop нажмите сочетание клавиш <kbd>Ctrl &#43; F12</kbd> или используйте кнопку перехода ниже.<br> \nЛогин по умолчанию \n	<strong>demo</strong>, пароль <strong>demouser</strong>. <br> \nЕсли вы при установке задали свой логин и пароль, то используйте свои данные при авторизации.\n</p>\n<p>\n	<input value="Переход в панель управления" onclick="window.location.replace(''..phpshop/admpanel/'');" type="button">\n</p>\n<h2>Тестовая база</h2>\nПри установке магазина заполняется тестовая товарная база для демонстрации возможностей программы. Для очистки тестовой базы следует в панели управления магазином перейти в меню <kbd>База</kbd> - <kbd>SQL запрос к базе</kbd> и выбрать в выпадающем списке опцию <strong>"Очистить базу"</strong>. Обращаем Ваше внимание, что очистится вся  база с момента начала работы сайта.', '1', 0, '1400508757', '1', 'Администрирование PHPShop', '');


-- --------------------------------------------------------

-- 
-- Структура таблицы `phpshop_photo_categories`
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
-- Структура таблицы `phpshop_photo`
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
-- Структура таблицы `phpshop_rssgraber`
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
-- Дамп данных таблицы `phpshop_rssgraber`
-- 

INSERT INTO `phpshop_rssgraber` (`id`, `link`, `day_num`, `news_num`, `enabled`, `start_date`, `end_date`, `last_load`) VALUES
(1, 'http://www.phpshop.ru/rss/', 1, 3, '1', 1257714000, 1953700800, 1200616000);


-- --------------------------------------------------------

-- 
-- Структура таблицы `phpshop_rssgraber_jurnal`
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
-- Структура таблицы `phpshop_send_mail`
-- 

CREATE TABLE `phpshop_send_mail` (
  `id` int(64) NOT NULL auto_increment,
  `date` varchar(32) default '',
  `mail` varchar(32) default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_system`
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
-- Дамп данных таблицы `phpshop_system`
--

INSERT INTO `phpshop_system` (`id`, `name`, `company`, `num_row`, `skin`, `admin_mail`, `title`, `keywords`, `skin_choice`, `tel`, `meta`, `admoption`, `rss_use`, `addres`, `logo`) VALUES
(1, 'Управление сайтом', 'PHPShop Software', 5, 'diggi', 'admin@localhost', 'PHPShop CMS Free 5  - управление сайтом', 'управление сайтом, phpshop', '1', '(495) 111-222-333', 'Описание возможностей PHPShop CMS', 'a:40:{s:5:"theme";s:7:"default";s:6:"editor";s:7:"default";s:9:"ace_theme";s:4:"dawn";s:9:"adm_title";s:0:"";s:18:"rss_graber_enabled";s:1:"1";s:9:"user_skin";i:0;s:20:"templateshop_enabled";i:0;s:5:"img_w";s:3:"500";s:5:"img_h";s:3:"500";s:14:"width_podrobno";s:3:"100";s:17:"image_result_path";s:0:"";s:6:"img_tw";s:3:"150";s:6:"img_th";s:3:"150";s:12:"width_kratko";s:3:"100";s:14:"watermark_text";s:0:"";s:20:"watermark_text_color";s:7:"#cccccc";s:19:"watermark_text_size";s:2:"20";s:19:"watermark_text_font";s:9:"Astronaut";s:15:"watermark_right";s:2:"10";s:16:"watermark_bottom";s:2:"10";s:20:"watermark_text_alpha";s:2:"80";s:15:"watermark_image";s:0:"";s:17:"image_save_source";i:0;s:21:"image_adaptive_resize";i:0;s:15:"image_save_name";i:0;s:21:"watermark_big_enabled";i:0;s:24:"watermark_source_enabled";i:0;s:14:"mail_smtp_host";s:0:"";s:14:"mail_smtp_port";s:0:"";s:14:"mail_smtp_user";s:0:"";s:14:"mail_smtp_pass";s:0:"";s:17:"mail_smtp_replyto";s:0:"";s:17:"recaptcha_enabled";s:1:"1";s:14:"recaptcha_pkey";s:0:"";s:14:"recaptcha_skey";s:0:"";s:14:"dadata_enabled";s:1:"1";s:12:"dadata_token";s:0:"";s:17:"mail_smtp_enabled";i:0;s:15:"mail_smtp_debug";i:0;s:14:"mail_smtp_auth";i:0;}', 0, 'Москва, ул. Физическая, дом 1.', '/UserFiles/Image/demo/your_logo.png');



-- 
-- Структура таблицы `phpshop_users`
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
