<?php

class verbox {

    public function __construct() {

    }

    public function option() {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['verbox']['verbox_system']);
        return $PHPShopOrm->select();
    }
}

function verbox_footer_hook() {

    $verbox = new verbox();
    $options = $verbox->option();
    $dis = '
    '.str_replace('&#43;','+',$options['code']);
    echo $dis;
}

$addHandler = array ('footer' => 'verbox_footer_hook');
?>