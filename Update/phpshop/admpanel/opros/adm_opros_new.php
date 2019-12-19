<?php

$TitlePage = __('�������� ������');
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['opros_categories']);

// ��������� ���
function actionStart() {
    global $PHPShopGUI, $PHPShopOrm, $PHPShopModules, $TitlePage;

    // �������
    $data['id'] = getLastID();
    $data['flag']=1;

    $PHPShopGUI->addJSFiles('./opros/gui/opros.gui.js');

    $PHPShopGUI->setActionPanel($TitlePage, false, array('������� � �������������', '��������� � �������'));

    // ���������� �������� 
    $Tab1 = $PHPShopGUI->setCollapse(__('����������'), $PHPShopGUI->setField(__("���������"), $PHPShopGUI->setInput("text.requared", "name_new", $data['name'])) .
            $PHPShopGUI->setField(__("���������"), $PHPShopGUI->setTextarea("dir_new", $data['dir']) . $PHPShopGUI->setHelp("������: page/,news/. ����� ������� ��������� ������� ����� �������.")) .
            $PHPShopGUI->setField(__("������"), $PHPShopGUI->setRadio("flag_new", 1, "��������", $data['flag']) . $PHPShopGUI->setRadio("flag_new", 0, "���������", $data['flag'])));

    // ��������
    $Tab1.=$PHPShopGUI->setCollapse(__('��������'), $PHPShopGUI->setField(null, $PHPShopGUI->loadLib('tab_value', $data)));


    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1, 350));

    // ������ ������ �� ��������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

    // ����� ������ ��������� � ����� � �����
    $ContentFooter = $PHPShopGUI->setInput("submit", "saveID", "��", "right", 70, "", "but", "actionInsert.opros.create") . $PHPShopGUI->setInput("hidden", "rowID", $data['id']);

    // �����
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

/**
 * ID ����� ������ � �������
 * @return integer 
 */
function getLastID() {
    $PHPShopOrm = new PHPShopOrm();
    $PHPShopOrm->sql = 'SHOW TABLE STATUS LIKE "' . $GLOBALS['SysValue']['base']['opros_categories'] . '"';
    $data = $PHPShopOrm->select();
    if (is_array($data)) {
        return $data[0]['Auto_increment'];
    }
}

// ������� ������
function actionInsert() {
    global $PHPShopOrm, $PHPShopModules;


    // �������� ������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->insert($_POST, '_new');

    if ($_POST['saveID'] == '������� � �������������')
        header('Location: ?path=' . $_GET['path'] . '&id=' . $_POST['rowID']);
    else
        header('Location: ?path=' . $_GET['path']);
    return $action;
}

// ��������� �������
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
?>