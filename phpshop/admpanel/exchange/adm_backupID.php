<?php

// ������� ��������
function actionDelete() {
    global $PHPShopModules;

    // �������� ������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    if (PHPShopSecurity::true_skin($_POST['file']))
        $action = @unlink("./dumper/backup/" . $_POST['file']);
    else
        $action = false;


    return array("success" => $action);
}

// ��������� �������
$PHPShopGUI->getAction();

?>