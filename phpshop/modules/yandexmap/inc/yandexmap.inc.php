<?php

if (!defined("OBJENABLED"))
    exit(header('Location: /?error=OBJENABLED'));

class PHPShopYandexMapElement extends PHPShopElements {

    function __construct() {
        $this->debug=false;
        $this->objBase=$GLOBALS['SysValue']['base']['yandexmap']['yandexmap_system'];
        parent::__construct();
    }

    // ����� ������
    function yandexmap($num=false) {
        $data = $this->PHPShopOrm->select();
        return $data['code'];
    }

}

// ����� 
$PHPShopYandexMapElement = new PHPShopYandexMapElement();
$PHPShopYandexMapElement->init('yandexmap');

?>