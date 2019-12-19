<?php

function userorderpaymentlink_mod_nextpay_hook($obj, $PHPShopOrderFunction) {

    // Настройки модуля
    include_once(dirname(__FILE__) . '/mod_option.hook.php');
    $PHPShopNextPayArray = new PHPShopNextPayArray();
    $option = $PHPShopNextPayArray->getArray();


    // Контроль оплаты от статуса заказа
    if ($PHPShopOrderFunction->order_metod_id == 10016)
    if ($PHPShopOrderFunction->getParam('statusi') == $option['status'] or empty($option['status'])) {
        // Номер счета
        $mrh_ouid = explode("-", $PHPShopOrderFunction->objRow['uid']);
        $order_id = $mrh_ouid[0] . "" . $mrh_ouid[1];

        // Сумма покупки
        $amount = $PHPShopOrderFunction->getTotal();

        // Сумма покупки
        $nextpay_product_id = $option['merchant_key'];
       


        $return = PHPShopText::a("https://www.nextpay.ru/bankpay/?product_id=".$nextpay_product_id."&ext_order_cost=".$amount."&seller_ext_order_id=".$order_id."&command=show_product_form_ext&np_email=".$PHPShopOrderFunction->getMail()."&np_payer=", 'Оплатить сейчас', 'Оплатить сейчас', false, false, '_blank', 'btn btn-success pull-right');
    } elseif ($PHPShopOrderFunction->getSerilizeParam('orders.Person.order_metod') == 10016)
        $return = ', Заказ обрабатывается менеджером';

    return $return;
}

$addHandler = array('userorderpaymentlink' => 'userorderpaymentlink_mod_nextpay_hook');
?>