<?php

// Настройки модуля
PHPShopObj::loadClass("array");

class PHPShopNextPayArray extends PHPShopArray {
    function __construct() {
        $this->objType=3;
        $this->objBase='phpshop_modules_nextpay_system';
        parent::__construct("status","title",'title_sub','merchant_key', 'merchant_key2','merchant_skey','link_top_text', 'link_text');
    }
}

?>
