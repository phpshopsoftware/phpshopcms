<?php

$TitlePage = __('�������� �������');
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['banner']);

// ��������� ���
function actionStart() {
    global $PHPShopGUI, $PHPShopSystem, $PHPShopOrm, $PHPShopModules;

    // �������
    $data['flag'] = $data['enabled']= 1;
    $PHPShopGUI->setActionPanel(__("�������� �������"), false, array('��������� � �������'));

    // ���������� �������� 1
    $Tab1 = $PHPShopGUI->setField("���:", $PHPShopGUI->setInput("text", "name_new", $data['name'], false, 500)) .
            $PHPShopGUI->setField("������:", $PHPShopGUI->setRadio("enabled_new", 1, "��������", $data['enabled']) . $PHPShopGUI->setRadio("enabled_new", 0, "���������", $data['enabled']));


    // �������� 
    $PHPShopGUI->setEditor($PHPShopSystem->getSerilizeParam("admoption.editor"));
    $oFCKeditor = new Editor('content_new');
    $oFCKeditor->Height = '300';
    $oFCKeditor->Value = $data['content'];

    // ���������� �������� 2
    $Tab1.= $PHPShopGUI->setField("����������", $oFCKeditor->AddGUI());


    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1));

    // ������ ������ �� ��������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);


    // ����� ������ ��������� � ����� � �����
   $ContentFooter = $PHPShopGUI->setInput("submit", "saveID", "��", "right", 70, "", "but", "actionInsert.banner.create");

    // �����
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}


// ������� ������
function actionInsert() {
    global $PHPShopOrm, $PHPShopModules;

    // �������� ������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $action = $PHPShopOrm->insert($_POST);
    header('Location: ?path=' . $_GET['path']);
    return $action;
}

// ����� ����� ��� ������
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');

// ��������� �������
$PHPShopGUI->getAction();
?>



