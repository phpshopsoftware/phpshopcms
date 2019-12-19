<?php

/**
 * Настройка шаблона из внешней части
 * @package PHPShopAjaxElements
 */
session_start();

$_classPath = "../";


include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
PHPShopObj::loadClass("array");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("string");
PHPShopObj::loadClass("security");

// Проверка прав админа
if (!empty($_SESSION['logPHPSHOP']) and PHPShopSecurity::true_skin($_COOKIE[$_REQUEST['template'].'_theme'])) {

    $PHPShopSystem = new PHPShopSystem();

    if ($GLOBALS['SysValue']['template_theme']['demo'] != 'true') {

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name3']);
        $admoption = unserialize($PHPShopSystem->getParam('admoption'));
        $admoption[$_REQUEST['template'].'_theme'] = $_COOKIE[$_REQUEST['template'].'_theme'];
        $update['admoption_new'] = serialize($admoption);
        $PHPShopOrm->update($update);

        $_RESULT = array(
            "status" => "Шаблон изменен на ".$_COOKIE[$_REQUEST['template'].'_theme'].'.css',
            "success" => 1
        );

        if ($_REQUEST['type'] == 'json'){
            $_RESULT['status']=PHPShopString::win_utf8($_RESULT['status']);
            echo json_encode($_RESULT);
        }
    }
}
?>