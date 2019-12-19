<?php

if (!empty($GLOBALS['SysValue']['class']['cron'])) {
    include_once($GLOBALS['SysValue']['class']['cron']);
    $PHPShopCron = new PHPShopCron();
    $PHPShopCron->start();
}
else
    exit('PHPShop Report: Модуль "Cron" выключен.');
?>