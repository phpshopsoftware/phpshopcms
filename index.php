<?php

/**
 * Загрузчик ядра
 * @author PHPShop Software
 * @version PHPShop.CMS Free 5.3
 * @copyright PHPShop LLC © 2004-2018
 * @license https://www.phpshopcms.ru/doc/license.html
 */
//  UTF-8 Default Charset Fix
if (stristr(ini_get("default_charset"), "utf")) {
    ini_set("default_charset", "cp1251");
}

// PHP Version Warning
if (floatval(phpversion()) < 5.2) {
    exit("PHP " . phpversion() . " is not supported");
}

// Запускаем сессию
session_start();

// Включаем таймер
$start_time = microtime(true);

// Парсируем установочный файл
include("./phpshop/class/base.class.php");
$PHPShopBase = new PHPShopBase("./phpshop/inc/config.ini", true, true);

// Файлы локализации
$GLOBALS['_localePath'] = 'phpshop/locale/';

// Сжатие данных GZIP
if ($SysValue['my']['gzip'] == "true")
    include($SysValue['file']['gzip']);

// Подключаем библиотеки
include($SysValue['class']['obj']);
include($SysValue['class']['array']);
include($SysValue['class']['category']);
include($SysValue['class']['system']);
include($SysValue['class']['nav']);
include($SysValue['class']['security']);
include($SysValue['class']['core']);
include($SysValue['class']['elements']);
include($SysValue['class']['lang']);
include($SysValue['class']['date']);
include($SysValue['class']['debug']);
include($SysValue['class']['analitica']);

// Системные настройки
$PHPShopSystem = new PHPShopSystem();

// Навигация
$PHPShopNav = new PHPShopNav();

// Отладка
$PHPShopDebug = new PHPShopDebug();

// Подключаем модули
include($SysValue['file']['elements']);
include($SysValue['file']['catalog']);

// Подключаем модули autoload
if(is_array($SysValue['autoload']))
foreach ($SysValue['autoload'] as $val)
    if (is_file($val))
        include_once($val);

// Загрузка основной логики
include($SysValue['file']['autoload']);

// Расход памяти
$_MEM = null;
if (function_exists('memory_get_usage')) {
    $_MEM = round(memory_get_usage() / 1024, 2) . " Kb";
}

// Панель отладки
if ($SysValue['my']['debug'] == "true")
    $PHPShopDebug->compile();

// Benchmark
if ($SysValue['my']['benchmark'] == "true")
    echo "<!-- БД " . $SysValue['sql']['num'] . " запроса ~ " . substr(microtime(true) - $start_time, 0, 6) . "  " . $_MEM . ", Сборка " . $SysValue['upload']['version'] . " -->";

// Вставка рейтингов и счетчиков
include_once($SysValue['file']['footer']);

// Сжатие данных GZIP
if ($SysValue['my']['gzip'] == "true")
    GzDocOut($SysValue['my']['gzip_level'], $SysValue['my']['gzip_debug']);
?>