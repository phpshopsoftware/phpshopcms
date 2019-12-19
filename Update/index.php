<?php

/**
 * ��������� ����
 * @author PHPShop Software
 * @version PHPShop.CMS Free 5.1
 * @copyright PHPShop LLC � 2004-2016
 * @license http://phpshopcms.ru/license.html
 */

//  UTF-8 Default Charset Fix
if (stristr(ini_get("default_charset"), "utf")) {
    ini_set("default_charset", "cp1251");
}

// Short Open Tag Warning
if (ini_get("short_open_tag") == 0) {
    exit("php.ini -> short_open_tag ON");
}

// PHP Version Warning
if(floatval(phpversion()) < 5.2){
   exit("PHP ".phpversion()." is not supported");
} 

// ��������� ������
session_start();

// ������
function ParseTemplate($TemplateName) {
    global $SysValue;

    $file = newGetFile($SysValue['dir']['templates'] . chr(47) . $_SESSION['skin'] . chr(47) . $TemplateName);
    $string = newParser($file);

    // �������� ����
    $path_parts = pathinfo($_SERVER['PHP_SELF']);
    if (getenv("COMSPEC"))
        $dirSlesh = "\\";
    else
        $dirSlesh = "/";
    $root = $path_parts['dirname'] . "/";
    if ($path_parts['dirname'] != $dirSlesh) {
        $replaces = array(
            "/images\//i" => $SysValue['dir']['templates'] . chr(47) . $_SESSION['skin'] . "/images/",
            "/!images!\//i" => "images/",
            "/\/favicon.ico/i" => $root . "favicon.ico",
            "/java\//i" => $root . "java/",
            "/css\//i" => $root . "css/",
            "/phpshop\//i" => $root . "phpshop/",
            "/\/links\//i" => $root . "links/",
            "/\/files\//i" => $root . "files/",
            "/\/opros\//i" => $root . "opros/",
            "/\/page\//i" => $root . "page/",
            "/\/news\//i" => $root . "news/",
            "/\/gbook\//i" => $root . "gbook/",
            "/\/search\//i" => $root . "search/",
            "/\"\/\"/i" => $root,
            "/\/map\//i" => $root . "map/",
            "/\/rss\//i" => $root . "rss/",
            "/\/tagcloud\//i" => $root . "tagcloud/",
        );
    } else {
        $replaces = array(
            "/images\//i" => $SysValue['dir']['templates'] . chr(47) . $_SESSION['skin'] . "/images/",
            "/!images!\//i" => "images/",
            "/java\//i" => "/java/",
            "/css\//i" => "/css/",
            "/phpshop\//i" => "/phpshop/",
        );
    }
    $string = preg_replace(array_keys($replaces), array_values($replaces), $string);
    echo $string;
}

// ������ �������
function ParseTemplateReturn($TemplateName, $mod = false) {
    global $SysValue;
    $SysValue = $GLOBALS['SysValue'];
    if ($mod)
        $file = newGetFile($TemplateName);
    else
        $file = newGetFile($SysValue['dir']['templates'] . chr(47) . $_SESSION['skin'] . chr(47) . $TemplateName);
    $dis = newParser($file);
    return @$dis;
}

function evalstr($str) {
    ob_start();
    if ($GLOBALS['SysValue']['function']['guard'] == "true") {
        if (!allowedFunctions($str[2]))
            return ob_get_clean();
    }
    if (eval(stripslashes($str[2])) !== NULL) {
        echo ('<center style="color:red"><br><br><b>PHPShop Template Code: � ������� ���������� ������ ���������� php</b><br>');
        echo ('��� ���������� ������:');
        echo ('<pre>');
        echo ($str[2]);
        echo ('</pre></center>');
        return ob_get_clean();
    }
    return ob_get_clean();
}

//check for allowed functions to eval in template
function allowedFunctions($str) {
    $allowFunctions = array(
        'if',
        'else',
        'switch',
        'for',
        'foreach',
        'echo',
        'print',
        'print_r',
        'array',
        'chr',
        'str_replace',
        'empry'
    );

    $allowFunctions = array_merge($allowFunctions, explode(',', $GLOBALS['SysValue']['function']['allowed']));
    preg_match_all('/\s*([A-Za-z0-9_]+)\s*\(/isU', $str, $findedFunctions);
    $remElements = array_diff($findedFunctions[1], $allowFunctions);

    $denyFunctions = explode(',', $GLOBALS['SysValue']['function']['deny']);
    foreach ($denyFunctions as $deny)
        if (stristr($str, $deny))
            $remElements[] = $deny;

    if (count($remElements) > 0) {
        echo ('<br><br><b>� ������� ���������� ����������� �������</b><br>');
        echo ('������ ��������� ����������� �������:');
        echo ('<pre>');
        foreach ($remElements as $remElement) {
            echo ($remElement . '()<br>');
        }
        echo ('</pre><br>');
        echo ('������ ����������� ������� (�������� ���� ������� ����� � config.ini ������ [function]):');
        echo ('<pre>');
        foreach ($allowFunctions as $allowFunction) {
            echo ($allowFunction . '()<br>');
        }
        echo ('<br>');
        echo ('</pre><br>');
        return false;
    } else {
        return true;
    }
}

function SysValueReturn($m) {
    global $SysValue;
    return $SysValue["other"][$m[1]];
}

function newParser($string) {
    $newstring = @preg_replace_callback("/(@php)(.*)(php@)/sU", "evalstr", $string);
    $newstring = @preg_replace_callback("/@([a-zA-Z0-9_]+)@/", 'SysValueReturn', $newstring);
    return $newstring;
}

function Parser($string) {
    return newParser($string);
}

function ConstantR($array) {
    global $SysValue;
    if (!empty($SysValue['other'][$array[1]]))
        $string = $SysValue['other'][$array[1]];
    else
        $string = null;

    return $string;
}

function newGetFile($path) {
    $file = @file_get_contents($path);
    if (!$file)
        return false;
    return $file;
}

// �������� ������
$time = explode(' ', microtime());
$start_time = $time[1] + $time[0];

// ��������� ������������ ����
include("./phpshop/class/base.class.php");
$PHPShopBase = new PHPShopBase("./phpshop/inc/config.ini");

// ����� �����������
$GLOBALS['_localePath'] = 'phpshop/locale/';

// ������ ������ GZIP
if ($SysValue['my']['gzip'] == "true")
    include($SysValue['file']['gzip']);

// ���������� ������
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

// ��������� ���������
$PHPShopSystem = new PHPShopSystem();

// ���������
$PHPShopNav = new PHPShopNav();

// �������
$PHPShopDebug = new PHPShopDebug();

// ���������� ������
include($SysValue['file']['elements']);
include($SysValue['file']['catalog']);

// ����� �������
if ($PHPShopSystem->getValue('skin_choice')) {

    if (isset($_REQUEST['skin'])) {
        if (file_exists("phpshop/templates/" . $_REQUEST['skin'] . "/index.html")) {
            $skin = $_REQUEST['skin'];
            if (PHPShopSecurity::true_login($_REQUEST['skin']))
                $_SESSION['skin'] = $_REQUEST['skin'];
        }
    }
    elseif (empty($_SESSION['skin'])) {
        $skin = $PHPShopSystem->getValue('skin');
        $_SESSION['skin'] = $skin;
    }
} else {
    $skin = $PHPShopSystem->getValue('skin');
    $_SESSION['skin'] = $skin;
}

// �������� ������������� �����
function Open($page) {
    global $SysValue;
    $page = $page . ".php";
    $handle = @opendir('pages');
    while ($file = readdir($handle)) {
        if ($file == $page) {
            return $page;
            exit;
        }
    }
    return $SysValue['my']['index'];
}

// ���������� ������ autoload
foreach ($SysValue['autoload'] as $val)
    if (is_file($val))
        include_once($val);

// �������� install
function GetFileInstall() {
    global $SysValue;
    $filename = "./install/";
    if (is_dir($filename))
        PHPShopBase::errorConnect(105, '���������� ���������', '������� ����� install');
}

// �������� install
if (!getenv("COMSPEC"))
    GetFileInstall();

// �������� �������� ������
include($SysValue['file']['autoload']);

// ��������� ������
$time = explode(' ', microtime());
$seconds = ($time[1] + $time[0] - $start_time);
$seconds = substr($seconds, 0, 6);

// ������ ������
$_MEM = '';
if (function_exists('memory_get_usage')) {
    $mem = memory_get_usage();
    $_MEM = round($mem / 1024, 2) . " Kb";
}

// ������ �������
if ($SysValue['my']['debug'] == "true")
    $PHPShopDebug->compile();

// benchmark
if ($SysValue['my']['benchmark'] == "true")
    echo "<!-- �� " . $SysValue['sql']['num'] . " ������� ~ $seconds  $_MEM, ������ " . $SysValue['upload']['version'] . " -->";

// ������� ������ ������ GZIP
if ($SysValue['my']['gzip'] == "true")
    GzDocOut($SysValue['my']['gzip_level'], $SysValue['my']['gzip_debug']);
?>