<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.formgenerator.formgenerator_forms"));

// ������� ������
function actionInsert() {
    global $PHPShopOrm;
    if (empty($_POST['user_mail_copy_new']))
        $_POST['user_mail_copy_new'] = 0;
    if (empty($_POST['enabled_new']))
        $_POST['enabled_new'] = 0;

    $action = $PHPShopOrm->insert($_POST);
    header('Location: ?path=' . $_GET['path']);
    return $action;
}

// ��������� ������� ��������
function actionStart() {
    global $PHPShopGUI, $PHPShopSystem;


    if (is_file('../modules/formgenerator/templates/formgenerator.tpl'))
        $content = file_get_contents('../modules/formgenerator/templates/formgenerator.tpl');

    $Tab1 = $PHPShopGUI->setField('��������:', $PHPShopGUI->setInputText(false, 'name_new', '����� �����'));
    $Tab1.=$PHPShopGUI->setField('������:', $PHPShopGUI->setInputText('http://' . $_SERVER['SERVER_NAME'] . '/formgenerator/', 'path_new', 'example'));
    $Tab1.=$PHPShopGUI->setField('E-mail:', $PHPShopGUI->setInputText(false, 'mail_new', $PHPShopSystem->getParam("adminmail2")));
    $Tab1.=$PHPShopGUI->setline() . $PHPShopGUI->setField('������:', $PHPShopGUI->setCheckbox('enabled_new', '1', '����� �� �����', 1) .
                    $PHPShopGUI->setCheckbox('user_mail_copy_new', '1', '������� ����� ������������ �� e-mail', 1));
    $Tab1.=$PHPShopGUI->setField('��������� ����� ��������:', $PHPShopGUI->setTextarea('success_message_new', '������ �������, ���� ��������� �������� � ����.', false, false, 200));
    $Tab1.=$PHPShopGUI->setField('��������� � ���������� ������������ �����:', $PHPShopGUI->setTextarea('error_message_new', '������ ���������� �����. ��������� ��� ����, ���������� ����������� (*).'));
    $Tab1.= $PHPShopGUI->setField('�������� � ���������:', $PHPShopGUI->setInputText(false, 'dir_new', '') . $PHPShopGUI->setHelp('������: /page/about.html,/page/company.html'));


    // �������� 1
    $PHPShopGUI->setEditor('ace', true);

    $oFCKeditor = new Editor('content_new');
    $oFCKeditor->Height = '520';
    $oFCKeditor->Value = $content;
    $Tab2 = $oFCKeditor->AddGUI();

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1), array("����������", $Tab2));

    // ����� ������ ��������� � ����� � �����
    $ContentFooter = $PHPShopGUI->setInput("submit", "saveID", "���������", "right", false, false, false, "actionInsert.modules.create");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// ��������� �������
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>