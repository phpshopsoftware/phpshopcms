<?php

$_classPath = $_SERVER['DOCUMENT_ROOT']."/phpshop/";
include($_classPath . "class/obj.class.php");

PHPShopObj::loadClass("parser");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("modules");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("mail");


$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
$PHPShopBase->chekAdmin();

// Настройки модуля
include_once(dirname(__FILE__) . '/../../hook/mod_option.hook.php');
$PHPShopNextPayArray = new PHPShopNextPayArray();
$option = $PHPShopNextPayArray->getArray();

if ($_POST['type'] == 'link') {
    $data = array();
    $data['text'] = 'https://www.nextpay.ru/buy/index.php?command=show_product_form_ext&product_id=' . $option['merchant_key2'] . '&seller_ext_order_id=' . $_POST['oder'] . '&ext_order_cost=' . $_POST['sum'] . '';
    $jdata = json_encode($data);
    echo $jdata;
    die();
}

if ($_POST['type'] == 'email') {

    $link = 'https://www.nextpay.ru/buy/index.php?command=show_product_form_ext&product_id=' . $option['merchant_key2'] . '&seller_ext_order_id=' . $_POST['oder'] . '&ext_order_cost=' . $_POST['sum'] . '';

    // получатель
    $to = $_POST['mail'];

    $PHPShopSystem = new PHPShopSystem();

    // текст письма
    PHPShopParser::set("shopName", $PHPShopSystem->getName());
    PHPShopParser::set("orderUid", $_POST['oder']);
    PHPShopParser::set("orderSum", $_POST['sum']);
    PHPShopParser::set("payLink", $link);
    PHPShopParser::set("shopLink", $_SERVER['DOCUMENT_ROOT']);
    PHPShopParser::set("shopLogo", $PHPShopSystem->getLogo());

    $message = PHPShopParser::file($_classPath . 'modules/nextpay/templates/link_form.tpl', true);

    // тема письма
    $subject = 'Ссылка для оплаты заказа от интернет-магазина "'.$PHPShopSystem->getName().'"';

    // Отправляем
    new PHPShopMail($to,$PHPShopSystem->getParam('adminmail2'), $subject, $message, true);

    $result = array();
    $result['text'] = 'Форма оплаты отправлена на E-mail';
    $result['text'] = iconv("windows-1251", "UTF-8", $result['text']);
    $jdata = json_encode($result);
    echo $jdata;
    die();
}











