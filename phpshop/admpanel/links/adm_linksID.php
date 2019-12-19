<?php

$TitlePage=__('�������������� ������ #'.$_GET['id']);
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['links']);

// ��������� �����
function setSelectChek($n) {
    $i = 1;
    while ($i <= 10) {
        if ($n == $i)
            $s = "selected"; else
            $s = "";
        $select[] = array($i, $i, $s);
        $i++;
    }
    return $select;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm, $PHPShopModules;

    // �������
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_GET['id'])));

    $PHPShopGUI->setActionPanel(__("�������������� ������: ".$data['name']), array('�������'),array('���������','��������� � �������'));
    
    $Select1 = setSelectChek(1);

    // ���������� �������� 1
    $Tab1 =
            $PHPShopGUI->setField("������:", $PHPShopGUI->setInputText(null,"name_new", $data['name'])) .
            $PHPShopGUI->setField("���������:", $PHPShopGUI->setSelect("num_new", $Select1, 70, 1)) .
            $PHPShopGUI->setField("������:", $PHPShopGUI->setRadio("enabled_new", 1, "��������", $data['enabled']) . $PHPShopGUI->setRadio("enabled_new", 0, "���������", $data['enabled'])) .
            $PHPShopGUI->setField("������:", $PHPShopGUI->setInput("text", "link_new", $data['link'])) .
            $PHPShopGUI->setLine() .
            $PHPShopGUI->setField("��������:", $PHPShopGUI->setTextarea("content_new", $data['content']));


    $Tab1.=$PHPShopGUI->setField("��� ������:", $PHPShopGUI->setTextarea("image_new", $data['image']));

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1));

    // ������ ������ �� ��������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

    // ����� ������ ��������� � ����� � �����
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "delID", "�������", "right", 70, "", "but", "actionDelete.links.edit") .
            $PHPShopGUI->setInput("submit", "editID", "���������", "right", 70, "", "but", "actionUpdate.links.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "���������", "right", 80, "", "but", "actionSave.links.edit");

// �����
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// ������� ��������
function actionDelete() {
    global $PHPShopOrm;
    $action = $PHPShopOrm->delete(array('id' => '=' . $_POST['rowID']));
    return array('success'=>$action);
}

/**
 * ����� ����������
 */
function actionSave() {

    // ���������� ������
    actionUpdate();

    header('Location: ?path='.$_GET['path']);
}

// ������� ����������
function actionUpdate() {
    global $PHPShopOrm, $PHPShopModules;
    
    // �������� ������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    if (empty($_POST['enabled_new']))
        $_POST['enabled_new'] = 0;
    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['rowID']));
    return array('success'=>$action);
}

// ��������� ������� 
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setAction($_GET['id'], 'actionStart', 'none');

?>