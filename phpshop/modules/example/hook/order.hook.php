<?php

function ordercartforma_hook($val,$option,$rout) {
    if($rout == 'END') {
        PHPShopParser::set('cart_name','Hook -> '.__FUNCTION__.'() -> '.__FILE__);
        PHPShopParser::set('currency','hook.');
    }
}

function delivery_hook($obj) {
    $obj->title='Hook';
    $obj->set('orderDelivery',PHPShopText::message('Example Hook -> '.__FUNCTION__.'() -> '.__FILE__));
    return true;
}

$addHandler=array
        (
        'ordercartforma'=>'ordercartforma_hook',
        '#delivery'=>'delivery_hook'

);

?>