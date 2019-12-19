<?php

function lock_hook($obj, $row, $rout) {

    
    if($rout == 'START'){
    
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['lock']['lock_system']);
    $option = $PHPShopOrm->select();

    if ($option['flag'] == 2) {

        if ((!isset($_SERVER['PHP_AUTH_USER'])) || !(($_SERVER['PHP_AUTH_USER'] == $option['login']) && ( $_SERVER['PHP_AUTH_PW'] == $option['password'] ))) {
            header("WWW-Authenticate: Basic entrer=\"Admin Login\"");
            header("HTTP/1.0 401 Unauthorized");
            return  die("Not authorized");
        }
    }
    }
}

$addHandler = array
    (
    'topMenu' => 'lock_hook'
);
?>