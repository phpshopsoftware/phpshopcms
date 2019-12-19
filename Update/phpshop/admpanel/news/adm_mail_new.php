<?php

$TitlePage = __('���������� ������ � ��������');
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['send_mail']);

// ��������� ���
function actionStart() {
    global $PHPShopGUI, $PHPShopModules;


    // �������� ������
    $data = $_GET['data'];

    $PHPShopGUI->setActionPanel(__("���������� ������ � ��������"), false, array('��������� � �������'));

    // ���������� �������� 1
    $Tab1 = $PHPShopGUI->setField("E-mail", $PHPShopGUI->setInput('text.required', "mail_new", $_GET['mail'], false, '200'));

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

    $_POST['date_new']=date("d-m-y");
    $action = $PHPShopOrm->insert($_POST);
    

    header('Location: ?path=' . $_GET['path']);

    return $action;
}

// ��������� �������
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>