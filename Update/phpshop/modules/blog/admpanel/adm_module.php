<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.blog.blog_system"));

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

    if ($data['flag'] == 0)
        $s0 = "selected";
    else
        $s1 = "selected";

    $Select[] = array("������", 1, $s1);
    $Select[] = array("�����", 0, $s0);

    $Tab1 = '<hr>'.$PHPShopGUI->setField("������", $PHPShopGUI->setCheckbox("enabled_new", 1, "����� ����� �� �����", $data['enabled']) . '<br>' .
            $PHPShopGUI->setCheckbox("enabled_menu_new", 1, "�������� � ���-���� ������", $data['enabled_menu']));
    $Tab1.= $PHPShopGUI->setField("������������ �����:", $PHPShopGUI->setSelect("flag_new", $Select, 100, 1));
    $Tab1.=$PHPShopGUI->setLine();
    $Tab1.=$PHPShopGUI->setField("���������:", $PHPShopGUI->setInputText(false, 'title_new', $data['title']));
    $Tab1.=$PHPShopGUI->setField("���-�� �� ��������:", $PHPShopGUI->setInputText(false, 'num_new', $data['num'], 100));
    $Info = '
     ��� ������������� ���������� ����� ������ ��������� ������� ����� ��������� ����� ������ ����� �� ����� � ����������� ���������� <kbd>@lastblogForma@</kbd>
     ��� ������� � ���� ������ � ������������ �����.';

    $Tab2 = $PHPShopGUI->setInfo($Info, 250, '97%');

    // ���������� �������� 2
    $Tab3 = $PHPShopGUI->setPay();

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1, 270), array("��������", $Tab2, 270), array("� ������", $Tab3, 270),array("����� ������� �����", 0,'?path=modules.dir.blog'));

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