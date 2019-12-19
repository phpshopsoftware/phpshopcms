<?php

/**
 * Обновление курсов валют из cbr.ru
 * Для включения поменяйте значение enabled на true
 */
// Включение
$enabled = false;

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

$url = "http://www.cbr.ru/scripts/XML_daily.asp";
$curs = $iso = array();

function get_timestamp($date) {
    list($d, $m, $y) = explode('.', $date);
    return mktime(0, 0, 0, $m, $d, $y);
}

$sql = 'select * from `phpshop_valuta`';
$result = mysqli_query($link_db,$sql);
while (@$row = mysqli_fetch_array(@$result)) {
    $iso[]=$row['iso'];
}

if (!$xml = simplexml_load_file($url))
    die('XML Error Library');


foreach ($xml->Valute as $m) {
    if(in_array($m->CharCode,$iso)){
        $val_kurs = (float) str_replace(",", ".", (string) $m->Value);
        $curs[(string) $m->CharCode] = 1 / $val_kurs;
    }
}

foreach ($curs as $key => $value) {
    $sql = "UPDATE `phpshop_valuta` SET `kurs` = '" . $value . "' WHERE `iso` ='" . $key . "';";
    mysqli_query($link_db,$sql);
}
?>