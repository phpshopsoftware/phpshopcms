<?php

$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['opros']);

/**
 * ����� ������
 */
function actionInsert() {
    global $PHPShopModules, $PHPShopOrm;

    // �������� ������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->insert($_POST,'_value');

    return array('success'=>$action);
}

// ��������� �������
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>