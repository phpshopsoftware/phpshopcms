<?php

$TitlePage = __('�������������� ������ #' . $_GET['id']);
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['opros_categories']);

// ��������� ���
function actionStart() {
    global $PHPShopGUI, $PHPShopOrm, $PHPShopModules, $TitlePage;

    // �������
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_GET['id'])));

    // ��� ������
    if (!is_array($data)) {
        header('Location: ?path=' . $_GET['path']);
    }

    $PHPShopGUI->addJSFiles('./opros/gui/opros.gui.js');

    $PHPShopGUI->setActionPanel($TitlePage, array('�������'), array('���������', '��������� � �������'));

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
    $ContentFooter = $PHPShopGUI->setInput("hidden", "rowID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "delID", "�������", "right", 70, "", "but", "actionDelete.opros.edit") .
            $PHPShopGUI->setInput("submit", "editID", "���������", "right", 70, "", "but", "actionUpdate.opros.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "���������", "right", 80, "", "but", "actionSave.opros.edit");

    // �����
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// ������� ��������
function actionDelete() {
    global $PHPShopOrm, $PHPShopModules;

    // �������� ������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);


    $PHPShopOrm->delete(array('id' => '=' . $_POST['rowID']));
    
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['opros']);
    $action = $PHPShopOrm->delete(array('category' => '=' . $_POST['rowID']));
    return array('success' => $action);
}

/**
 * ����� ����������
 */
function actionSave() {

    // ���������� ������
    actionUpdate();

    header('Location: ?path=' . $_GET['path']);
}

// ������� ����������
function actionUpdate() {
    global $PHPShopOrm, $PHPShopModules;

    // �������� ������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);


    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['rowID']));
    return array('success' => $action);
}

// ��������� �������
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setAction($_GET['id'], 'actionStart', 'none');
?>