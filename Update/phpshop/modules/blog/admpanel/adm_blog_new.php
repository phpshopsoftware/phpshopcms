<?php

$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.blog.blog_log"));

// ��������� ���
function actionStart() {
    global $PHPShopGUI, $PHPShopSystem;
    
     // ����� ����
    $PHPShopGUI->addJSFiles('./js/bootstrap-datetimepicker.min.js','./news/gui/news.gui.js');
    $PHPShopGUI->addCSSFiles('./css/bootstrap-datetimepicker.min.css');
    
    $data['date']=time();
    $data['title']='����� ������ � ����';

    // �������� 1
    $PHPShopGUI->setEditor($PHPShopSystem->getSerilizeParam("admoption.editor"), true);
    $oFCKeditor = new Editor('description_new', true);
    $oFCKeditor->Height = '320';
    $oFCKeditor->ToolbarSet = 'Normal';

    // ���������� �������� 1
    $Tab1 = '<hr>'.$PHPShopGUI->setField("����:", $PHPShopGUI->setInputDate("date_new", PHPShopDate::get($data['date']))).
            $PHPShopGUI->setField("���������:", $PHPShopGUI->setInput("text.required", "title_new", $data['title']));

    $Tab1.=$PHPShopGUI->setField("�����:", $oFCKeditor->AddGUI());

    // �������� 2
    $oFCKeditor = new Editor('content_new');
    $oFCKeditor->Height = '550';
    $oFCKeditor->ToolbarSet = 'Normal';

    // ���������� �������� 2
    $Tab2 = $oFCKeditor->AddGUI();

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1), array("��������", $Tab2));

    // ����� ������ ��������� � ����� � �����
    $ContentFooter=$PHPShopGUI->setInput("submit","saveID","���������","right",false,false,false,"actionInsert.modules.create");

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

// ��������� ������� 
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
?>