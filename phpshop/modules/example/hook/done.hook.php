<?php

function index_hook($obj, $row, $rout) {
    if ($rout == 'END')
        $obj->set('orderMesage', PHPShopText::notice('Hook -> ' . __FUNCTION__ . '() -> ' . __FILE__));
}

function write_hook($obj, $row, $rout) {
    if ($rout == "END") {
        $order = unserialize($obj->order);
        $order['Person']['name_person'] = 'Hook';
        $obj->order = serialize($order);
    }
}

function mailcartforma_hook($val, $option) {
    $dis =
            'Hook -> ' . __FUNCTION__ . '() -> ' . __FILE__ . " (" . $val['num'] . " רע. * " . $val['price'] . ") -- " . ($val['price'] * $val['num']) . " " . $option['currency'] . "
";
    return $dis;
}

$addHandler = array
    (
    'index' => 'index_hook',
    'write' => 'write_hook',
    'mailcartforma' => 'mailcartforma_hook'
);
?>