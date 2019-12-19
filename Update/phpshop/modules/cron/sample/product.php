<?php

/**
 * Снятие с продаж товара с датой изменения менее Х дней
 */

// Включение [true/false]
$enabled = false;

// Дни [1-100]
$day=3;

// 1 - убирать с сайта, 2 - под заказ
$option=2;

// Авторизация
if (empty($enabled))
    exit("Ошибка авторизации!");

$_classPath = "../../../";
$SysValue = parse_ini_file($_classPath . "inc/config.ini", 1);


// MySQL hostname
$host = $SysValue['connect']['host'];
//MySQL basename
$dbname = $SysValue['connect']['dbase'];
// MySQL user
$uname = $SysValue['connect']['user_db'];
// MySQL password
$upass = $SysValue['connect']['pass_db'];

$link_db = @mysqli_connect($host, $uname, $upass);
mysqli_select_db($link_db,$dbname);

switch($option){
    case 1:
        $sql="enabled='0'";
        break;
    
    case 2:
        $sql="sklad='1'";
    break;

    default: $sql="enabled='0'";
}

mysqli_query($link_db,"update phpshop_products set $sql where datas<".(time()-(86400*$day)));

echo "Выполнено";
?>