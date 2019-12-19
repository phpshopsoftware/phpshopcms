<?php

class envybox {

    public function __construct() {

    }

    public function option() {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['envybox']['envybox_system']);
        return $PHPShopOrm->select();
    }
}

function envybox_footer_hook() {

    $envybox = new envybox();
    $options = $envybox->option();
    $dis = '<!-- envybox -->
<link rel="stylesheet" href="https://cdn.envybox.io/widget/cbk.css">
<script type="text/javascript" src="https://cdn.envybox.io/widget/cbk.js?wcb_code='.$options[widget_id].'" charset="UTF-8" async></script>
<!--/ envybox -->    
';
    echo $dis;
}

$addHandler = array ('footer' => 'envybox_footer_hook');
?>