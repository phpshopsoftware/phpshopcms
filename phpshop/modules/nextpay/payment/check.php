<?php

/**
 * Обработчик валидации платежа NextPay
 */
session_start();

$_classPath = "../../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("payment");
PHPShopObj::loadClass("modules");
PHPShopObj::loadClass("system");

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini", true, true);

$PHPShopModules = new PHPShopModules($_classPath . "modules/");
$PHPShopModules->checkInstall('nextpay');


// Номер счета
function trueNumOrder($uid) {
    $last_num = substr($uid, -2);
    $total = strlen($uid);
    $ferst_num = substr($uid, 0, ($total - 2));
    return $ferst_num . "-" . $last_num;
}

$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
$data = $PHPShopOrm->select(array('*'), array('uid' => '="' . trueNumOrder($_REQUEST['seller_ext_order_id']) . '"'), false, array('limit' => 1));

if (is_array($data)) {
    if (number_format($data['sum'], 2,'.','') == number_format($_REQUEST['cost_general'], 2,'.','')) {
        echo "ok";
    } else {
        echo "bad cost";
    }
} else {
    echo "invalid_order_id";
}
?>