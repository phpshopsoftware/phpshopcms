<?php
$TitlePage = __('�������� �������');
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['news']);

function actionStart() {
    global $PHPShopGUI, $PHPShopModules,$PHPShopSystem;

    // �������
    $data['date'] = PHPShopDate::get();
    $data['title'] = __('������� �� ') . $data['date'];

    // datetimepicker
    $PHPShopGUI->addJSFiles('./js/bootstrap-datetimepicker.min.js','./js/jquery.waypoints.min.js','./news/gui/news.gui.js');
    $PHPShopGUI->addCSSFiles('./css/bootstrap-datetimepicker.min.css');
    
    $PHPShopGUI->setActionPanel(__("�������� �������"), false, array('��������� � �������'));

    // �������� 1
    $PHPShopGUI->setEditor($PHPShopSystem->getSerilizeParam("admoption.editor"));
    $oFCKeditor = new Editor('description_new');
    $oFCKeditor->Height = '270';
    $oFCKeditor->Value = $data['description'];

    // ���������� �������� 1
    $Tab1 = $PHPShopGUI->setField("����:", $PHPShopGUI->setInputDate("date_new", $data['date'])) .
            $PHPShopGUI->setField("���������:", $PHPShopGUI->setInput("text", "title_new", $data['title']));

    $Tab1.=$PHPShopGUI->setField("�����:", $oFCKeditor->AddGUI());


    // �������� 2
    $oFCKeditor2 = new Editor('content_new');
    $oFCKeditor2->Height = '550';
    $oFCKeditor2->Value = $data['content'];

    $Tab1.=$PHPShopGUI->setField("��������:", $oFCKeditor2->AddGUI());

    // ������ ������ �� ��������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);
    
        // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1));

    // ����� ������ ��������� � ����� � �����
    $ContentFooter = $PHPShopGUI->setInput("submit", "saveID", "��", "right", 70, "", "but", "actionInsert.news.create");

    // �����
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// ������� ����������
function actionInsert() {
    global $PHPShopOrm, $PHPShopModules;

    $_POST['datau_new'] = time();

    // �������� ������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);
    $action = $PHPShopOrm->insert($_POST);
    header('Location: ?path=' . $_GET['path']);
    return $action;
}

// ��������� �������
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');


?>
