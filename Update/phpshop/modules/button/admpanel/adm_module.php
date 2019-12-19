<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.button.button_system"));

// ������� ����������
function actionUpdate() {
    global $PHPShopOrm;
    if (empty($_POST['enabled_new']))
        $_POST['enabled_new'] = 0;
    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&install=check');
    return $action;
}


// ��������� ������� ��������
function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    // �������
    $data = $PHPShopOrm->select();

    switch ($data['enabled']) {
        case 0: $s0 = 'selected';
            break;
        case 1: $s1 = 'selected';
            break;
        case 2: $s2 = 'selected';
            break;
        case 3: $s3 = 'selected';
            break;
    }

    $value[] = array('��������', 0, $s0);
    $value[] = array('������', 1, $s1);
    $value[] = array('�����', 2, $s2);
    $value[] = array('������', 3, $s3);


    $info = '��� ������������ ������� ��������, ������� ������� �������� ������ "��������" � �������� ����������
        <kbd>@button@</kbd> � ���� ������ � ������ ��� �����.';
    $Tab1 = $PHPShopGUI->setInfo($info, 200, '96%');


    $Tab1.=$PHPShopGUI->setField('������������ �����', $PHPShopGUI->setSelect('enabled_new', $value));

    // ���������� �������� 2
    $Tab3 = $PHPShopGUI->setPay();

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1), array("� ������", $Tab3),array("����� ������", null,'?path=modules.dir.button'));

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