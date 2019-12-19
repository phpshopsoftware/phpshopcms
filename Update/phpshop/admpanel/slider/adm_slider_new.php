<?php

$TitlePage = __('�������� ��������');
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['slider']);


// ��������� ���
function actionStart() {
    global $PHPShopGUI, $PHPShopModules;

    $PHPShopGUI->setActionPanel(__("�������� ��������"), false, array('��������� � �������'));
    
    $data = array();

    // ���������� �������� 1
    $Tab1 = $PHPShopGUI->setField(__("�����������"), $PHPShopGUI->setIcon($data['image'], "image_new", false)) .
            $PHPShopGUI->setField(__("����"), $PHPShopGUI->setInput("text", "link_new", $data['link'], "none", 300) . $PHPShopGUI->setHelp("������: /pages/info.html ��� http://google.com")).
            $PHPShopGUI->setField(__("������"),$PHPShopGUI->setRadio("enabled_new", 1, "��������", $data['enabled']) . $PHPShopGUI->setRadio("enabled_new", 0, "���������", $data['enabled'])).
            $PHPShopGUI->setField(__("���������"), $PHPShopGUI->setInputText(false, 'num_new', $data['num'], 100)) .
            $PHPShopGUI->setField(__("��������"), $PHPShopGUI->setTextarea("alt_new", $data['alt']));


    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1, 350));

    // ������ ������ �� ��������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, null);

    // ����� ������ ��������� � ����� � �����
    $ContentFooter = $PHPShopGUI->setInput("submit", "saveID", "��", "right", 70, "", "but", "actionInsert.slider.create");

    // �����
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// ������� ������
function actionInsert() {
    global $PHPShopOrm, $PHPShopModules;
    
    $_POST['image_new'] = iconAdd();

    // �������� ������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $action = $PHPShopOrm->insert($_POST);
    header('Location: ?path=' . $_GET['path']);
    return $action;
}

// ���������� ����������� 
function iconAdd() {

    // ����� ����������
    $path = '/UserFiles/Image/';

    // �������� �� ������������
    if (!empty($_FILES['file']['name'])) {
        $_FILES['file']['ext'] = PHPShopSecurity::getExt($_FILES['file']['name']);
        if (in_array($_FILES['file']['ext'], array('gif', 'png', 'jpg'))) {
            if (move_uploaded_file($_FILES['file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $GLOBALS['dir']['dir'] . $path . $_FILES['file']['name'])) {
                $file = $GLOBALS['dir']['dir'] . $path . $_FILES['file']['name'];
            }
        }
    }

    // ������ ���� �� URL
    elseif (!empty($_POST['furl'])) {
        $file = $_POST['image_new'];
    }

    // ������ ���� �� ��������� ���������
    elseif (!empty($_POST['image_new'])) {
        $file = $_POST['image_new'];
    }


    if (!empty($file)) {
        return $file;
    }
}

// ��������� �������
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');

?>