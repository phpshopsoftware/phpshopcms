<?php

function success_mod_nextpay_hook($obj, $value) {
  
   if(!empty($_REQUEST['seller_ext_order_id'])){
       $obj->order_metod = 'modules" and id="10016';
       $obj->message();
        return true;
    }
}

$addHandler = array('index' => 'success_mod_nextpay_hook');
?>