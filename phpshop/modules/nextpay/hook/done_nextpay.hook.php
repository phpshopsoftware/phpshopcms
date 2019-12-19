<?php

function send_to_order_mod_nextpay_hook($obj, $value, $rout) {

    if ($rout == 'MIDDLE' and $value['order_metod'] == 10016) {

        // ��������� ������
        include_once(dirname(__FILE__) . '/mod_option.hook.php');
        $PHPShopNextPayArray = new PHPShopNextPayArray();
        $option = $PHPShopNextPayArray->getArray();

        // �������� ������ �� ������� ������
        if (empty($option['status'])) {

            // ����� �����
            $mrh_ouid = explode("-", $value['ouid']);
            $order_id= $mrh_ouid[0] . $mrh_ouid[1];

            // ����� �������
            $amount= number_format($obj->get('total'), 2, '.', '');
            $nextpay_product_id = $option['merchant_key'];

            $link=PHPShopText::a("https://www.nextpay.ru/bankpay/?product_id=".$nextpay_product_id."&ext_order_cost=".$amount."&seller_ext_order_id=".$order_id."&command=show_product_form_ext&np_email=".$_POST['mail']."&np_payer=".$_POST['fio_new'], "�������� ����� � $order_id ����� ��������� ������� NextPay", "�������� ����� � ".$order_id, false, false, false, 'btn btn-success');

            $obj->set('payment_forma', $link);
            $obj->set('payment_info', $option['title']);
            $forma = ParseTemplateReturn($GLOBALS['SysValue']['templates']['nextpay']['nextpay_payment_forma'], true);
        }
        else {
            $obj->set('mesageText', $option['title_sub'] );
            $forma = ParseTemplateReturn($GLOBALS['SysValue']['templates']['order_forma_mesage']);
        }

        $obj->set('orderMesage', $forma);
    }
}

$addHandler = array
    (
    'send_to_order' => 'send_to_order_mod_nextpay_hook'
);
?>