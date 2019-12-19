<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.returncall.returncall_system"));

// ���������� ������ ������
function actionBaseUpdate() {
    global $PHPShopModules, $PHPShopOrm;
    $PHPShopOrm->clean();
    $option = $PHPShopOrm->select();
    $new_version = $PHPShopModules->getUpdate($option['version']);
    $PHPShopOrm->clean();
    $action = $PHPShopOrm->update(array('version_new' => $new_version));
    return $action;
}

// ������� ����������
function actionUpdate() {
    global $PHPShopOrm;

    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&install=check');
    return $action;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    // �������
    $data = $PHPShopOrm->select();

    // �����
    $e_value[] = array('�� ��������', 0, $data['enabled']);
    $e_value[] = array('�����', 1, $data['enabled']);
    $e_value[] = array('������', 2, $data['enabled']);

    // ��� ������
    $w_value[] = array('�����', 0, $data['windows']);
    $w_value[] = array('����������� ����', 1, $data['windows']);

    // Captcha
    $c_value[] = array('��', 1, $data['captcha_enabled']);
    $c_value[] = array('���', 2, $data['captcha_enabled']);


    $Tab1 = $PHPShopGUI->setField('���������', $PHPShopGUI->setInputText(false, 'title_new', $data['title']));
    $Tab1.=$PHPShopGUI->setField('���������', $PHPShopGUI->setTextarea('title_end_new', $data['title_end']));
    $Tab1.=$PHPShopGUI->setField('����� ������', $PHPShopGUI->setSelect('enabled_new', $e_value, 200));
    $Tab1.=$PHPShopGUI->setField('��� ������', $PHPShopGUI->setSelect('windows_new', $w_value, 200));
    $Tab1.=$PHPShopGUI->setField('Captcha', $PHPShopGUI->setSelect('captcha_enabled_new', $c_value, 200));

    $info = '��� ������������ ������� �������� ������� ������� ������� ������ "�� ��������" � � ������ ������ �������� ����������
        <kbd>@returncall@</kbd> � ���� ������.
        <p>��� �������������� ����� ������ �������������� ������� <code>phpshop/modules/returncall/templates/</code></p>
        <p>��� ��������� �������� ������ ����������� <kbd>@returncall_captcha@</kbd> � ����� ��������� ������ <code>
        phpshop/modules/returncall/templates/returncall_forma.tpl</code></p>';

    $Tab2 = $PHPShopGUI->setInfo($info);

    // ����� �����������
    $Tab3 = $PHPShopGUI->setPay($data['serial'], false, $data['version'], true);

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1), array("����������", $Tab2), array("� ������", $Tab3),array("����� ������", null,'?path=modules.dir.returncall'));

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