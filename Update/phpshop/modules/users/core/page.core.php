<?php
function index_security_hook($obj,$row) {

    if(!empty($row['user_security']) and empty($_SESSION['userName'])) {
        $obj->set('pageContent',ParseTemplateReturn($GLOBALS['SysValue']['templates']['users']['users_forma'],true));
        $obj->set('pageTitle','“олько дл€ авторизованных пользователей');
        return true;
    }

}

$addHandler=array(
        'index'=>'index_security_hook',
        'page'=>'index_security_hook'
);

?>