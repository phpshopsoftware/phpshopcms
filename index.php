<?php

/**
 * ��������� ����
 * @author PHPShop Software
 * @version PHPShop.CMS Free 5.3
 * @copyright PHPShop LLC � 2004-2018
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

// ��������� ������
session_start();

// �������� ������
$start_time = microtime(true);

// ��������� ������������ ����
include("./phpshop/class/base.class.php");
$PHPShopBase = new PHPShopBase("./phpshop/inc/config.ini", true, true);

// ����� �����������
$GLOBALS['_localePath'] = 'phpshop/locale/';

// ������ ������ GZIP
if ($SysValue['my']['gzip'] == "true")
    include($SysValue['file']['gzip']);

// ���������� ����������
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

// ��������� ���������
$PHPShopSystem = new PHPShopSystem();

// ���������
$PHPShopNav = new PHPShopNav();

// �������
$PHPShopDebug = new PHPShopDebug();

// ���������� ������
include($SysValue['file']['elements']);
include($SysValue['file']['catalog']);

// ���������� ������ autoload
if(is_array($SysValue['autoload']))
foreach ($SysValue['autoload'] as $val)
    if (is_file($val))
        include_once($val);

// �������� �������� ������
include($SysValue['file']['autoload']);

// ������ ������
$_MEM = null;
if (function_exists('memory_get_usage')) {
    $_MEM = round(memory_get_usage() / 1024, 2) . " Kb";
}

// ������ �������
if ($SysValue['my']['debug'] == "true")
    $PHPShopDebug->compile();

// Benchmark
if ($SysValue['my']['benchmark'] == "true")
    echo "<!-- �� " . $SysValue['sql']['num'] . " ������� ~ " . substr(microtime(true) - $start_time, 0, 6) . "  " . $_MEM . ", ������ " . $SysValue['upload']['version'] . " -->";

// ������� ��������� � ���������
include_once($SysValue['file']['footer']);

// ������ ������ GZIP
if ($SysValue['my']['gzip'] == "true")
    GzDocOut($SysValue['my']['gzip_level'], $SysValue['my']['gzip_debug']);
?>