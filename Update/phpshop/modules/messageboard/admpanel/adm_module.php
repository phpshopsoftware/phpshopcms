<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.messageboard.messageboard_system"));

// ������� ����������
function actionUpdate() {
    global $PHPShopOrm;

    if (empty($_POST['enabled_new']))
        $_POST['enabled_new'] = 0;
    if (empty($_POST['enabled_menu_new']))
        $_POST['enabled_menu_new'] = 0;
    if (empty($_POST['flag_new']))
        $_POST['flag_new'] = 0;

    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&install=check');
    return $action;
}

// ��������� ������� ��������
function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    // �������
    $data = $PHPShopOrm->select();


    $Select[] = array("������", 1, $data['flag']);
    $Select[] = array("�����", 0, $data['flag']);

    $Tab1 = $PHPShopGUI->setField("������������ ����� ����������:", 
            $PHPShopGUI->setSelect("flag_new", $Select, 150, 1));
    $Tab1.= $PHPShopGUI->setField("������",$PHPShopGUI->setCheckbox("enabled_new", 1, "����� ����� �� �����", $data['enabled']).
            $PHPShopGUI->setCheckbox("enabled_menu_new", 1, "�������� � ���-���� ������", $data['enabled_menu']));
    $Tab1.=$PHPShopGUI->setField("������� �� �������:", $PHPShopGUI->setInputText(false, 'num_new', $data['num'],50));
    $Info = '��� ������������� ���������� ����� ������ ��������� ���������� ��������� ����� ������ ����� �� ����� � ����������� ���������� <kbd>@lastboardForma@</kbd>
     ��� ������� � ���� ������ � ������������ �����.';
    $Tab2 = $PHPShopGUI->setInfo($Info, 250, '97%');

    // ���������� �������� 2
    $Tab3 = $PHPShopGUI->setPay();

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1), array("��������", $Tab2), array("� ������", $Tab3));

    // ����� ������ ��������� � ����� � �����
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id']) .
            $PHPShopGUI->setInput("submit", "saveID", "���������", "right", 80, "", "but", "actionUpdate.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// ��������� �������
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>