<?php


// ��������� ������� ��������
function actionStart() {
    global $PHPShopGUI,$select_name;
    
    $PHPShopGUI->setActionPanel(__("��������� ������") . ' <span id="module-name">' . ucfirst($_GET['id']).'</span>', $select_name, null);

    // ���������� �������� 2
    $Tab2 = $PHPShopGUI->setPay();

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("� ������", $Tab2),array("����� �����", null,'?path=modules.dir.cron'),array("������ ����������", null,'?path=modules.dir.cron.log'));

    return true;
}

// ����� ����� ��� ������
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
?>