<?php

/**
 * ���������� �������� ������ ������� nextpay
 */
class PHPShopNextpaysuccess extends PHPShopCore {

    /**
     * �����������
     */
    function __construct() {

        parent::__construct();

        // ����
        $this->title = "����� ������� �������";
    }

    /**
     *  ��������� �� �������� �������
     */
    function index(){

        // ��������� ������
        include_once(dirname(__FILE__) . '/../hook/mod_option.hook.php');
        $PHPShopNextPayArray = new PHPShopNextPayArray();
        $option = $PHPShopNextPayArray->getArray();

        // ��������� ������������ �� �������� �������
        $text = PHPShopText::h3($option['link_top_text'], 'text-success') . $option['link_text'];
        $this->set('mesageText', $text);
        $this->set('orderMesage', ParseTemplateReturn($this->getValue('templates.order_forma_mesage')));

        // ���������� ������
        $this->parseTemplate($this->getValue('templates.order_forma_mesage_main'));
    }
}

?>