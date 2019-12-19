<?php

/**
 * Расчет цены для сортировки по прайсу среди мультивалютных товаров
 */
// Включение [true/false]
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
$sql = "select * from " . $SysValue['base']['currency'];
$result = mysqli_query($link_db,$sql);
while ($row = mysqli_fetch_array($result)) {
    if (empty($row['kurs']))
        $row['kurs'] = 1;
    mysqli_query($link_db,"update phpshop_products set price_search=price/" . $row['kurs'] . " where baseinputvaluta=" . $row['id']) or die(mysqli_error($link_db));
}

echo "Выполнено";
?>