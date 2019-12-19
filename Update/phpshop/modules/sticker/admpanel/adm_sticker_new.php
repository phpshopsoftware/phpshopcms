<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.sticker.sticker_forms"));

// ������� ������
function actionInsert() {
    global $PHPShopOrm;

    if (empty($_POST['enabled_new']))
        $_POST['enabled_new'] = 0;

    $action = $PHPShopOrm->insert($_POST);
    
    header('Location: ?path=' . $_GET['path']);
    return $action;
}

// ��������� ������� ��������
function actionStart() {
    global $PHPShopGUI, $PHPShopSystem;

    // �������
    $data['name'] = '����� ������';
    $data['enabled']=1;



    $Tab1 = $PHPShopGUI->setField('��������:', $PHPShopGUI->setInputText(false, 'name_new', $data['name'], 300));
    $Tab1.=$PHPShopGUI->setField('������:', $PHPShopGUI->setInputText('@sticker_', 'path_new', $data['path'], 200, '@'));
    $Tab1.=$PHPShopGUI->setField('�����:', $PHPShopGUI->setCheckbox('enabled_new', 1, '����� �� �����', $data['enabled']));
    $Tab1.=$PHPShopGUI->setField('���������:', $PHPShopGUI->setInputText(false, 'dir_new', $data['dir']) . $PHPShopGUI->setHelp('������: /page/about.html,/page/company.html'));


    $PHPShopGUI->setEditor($PHPShopSystem->getSerilizeParam("admoption.editor"), true);
    $oFCKeditor = new Editor('content_new', true);
    $oFCKeditor->Height = '320';
    $oFCKeditor->ToolbarSet = 'Normal';
    $oFCKeditor->Value = $data['content'];

    $Tab2 = $oFCKeditor->AddGUI();


    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1, 350), array("����������", $Tab2, 350));

    // ����� ������ ��������� � ����� � �����
    $ContentFooter=$PHPShopGUI->setInput("submit","saveID","���������","right",false,false,false,"actionInsert.modules.create");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// ��������� �������
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>