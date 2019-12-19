<?php

$TitlePage = __('���������� � ������ ������');
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['black_list']);

// ��������� ���
function actionStart() {
    global $PHPShopGUI, $PHPShopModules;


    // �������� ������
    $data = $_GET['data'];

    $PHPShopGUI->setActionPanel(__("���������� � ������ ������"), false, array('��������� � �������'));

    // ���������� �������� 1
    $Tab1 = $PHPShopGUI->setField("IP", $PHPShopGUI->setInput('text.required', "ip_new", $_GET['ip'], false, '200'));

    // ������ ������ �� ��������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1));

    // ����� ������ ��������� � ����� � �����
    $ContentFooter = $PHPShopGUI->setInput("submit", "saveID", "��", "right", 70, "", "but", "actionInsert.users.edit");

    // �����
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// ������� ������
function actionInsert() {
    global $PHPShopOrm, $PHPShopModules;

    // �������� ������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    if ($_POST['ip_new'] != $_SERVER['REMOTE_ADDR']) {
        $action = $PHPShopOrm->insert($_POST);
    }

    header('Location: ?path=' . $_GET['path']);

    return $action;
}

// ��������� �������
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>