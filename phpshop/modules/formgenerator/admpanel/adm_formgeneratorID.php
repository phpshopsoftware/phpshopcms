<?php

$TitlePage = __('�������������� ������ #' . intval($_GET['id']));

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.formgenerator.formgenerator_forms"));

// ������� ����������
function actionUpdate() {
    global $PHPShopOrm;

    if (empty($_POST['user_mail_copy_new']))
        $_POST['user_mail_copy_new'] = 0;
    if (empty($_POST['enabled_new']))
        $_POST['enabled_new'] = 0;

    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['rowID']));
    return array('success' => $action);
}

/**
 * ����� ����������
 */
function actionSave() {
    global $PHPShopGUI;


    // ���������� ������
    actionUpdate();

    header('Location: ?path=' . $_GET['path']);
}

// ��������� ������� ��������
function actionStart() {
    global $PHPShopGUI, $PHPShopOrm, $PHPShopSystem;

    // �������
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_GET['id'])));

    $Tab1 = $PHPShopGUI->setField('��������:', $PHPShopGUI->setInputText(false, 'name_new', $data['name']));
    $Tab1.=$PHPShopGUI->setField('������:', $PHPShopGUI->setInputText('http://' . $_SERVER['SERVER_NAME'] . '/formgenerator/', 'path_new', $data['path']));
    $Tab1.=$PHPShopGUI->setField('E-mail:', $PHPShopGUI->setInputText(false, 'mail_new', $data['mail']));
    $Tab1.=$PHPShopGUI->setline() . $PHPShopGUI->setField('������:', $PHPShopGUI->setCheckbox('enabled_new', '1', '����� �� �����', $data['enabled']) .
                    $PHPShopGUI->setCheckbox('user_mail_copy_new', '1', '������� ����� ������������ �� e-mail', 1));
    $Tab1.=$PHPShopGUI->setField('��������� ����� ��������:', $PHPShopGUI->setTextarea('success_message_new', $data['success_message'], false, false, 200));
    $Tab1.=$PHPShopGUI->setField('��������� � ���������� ������������ �����:', $PHPShopGUI->setTextarea('error_message_new', $data['error_message']));
    $Tab1.= $PHPShopGUI->setField('�������� � ���������:', $PHPShopGUI->setInputText(false, 'dir_new', $data['dir']) . $PHPShopGUI->setHelp('������: /page/about.html,/page/company.html'));
    $Tab3 = $PHPShopGUI->setTextarea('code', '@php
$PHPShopFormgeneratorElement = new PHPShopFormgeneratorElement();
echo $PHPShopFormgeneratorElement->forma("' . $data['path'] . '");
php@', 'none', '98%', 100) . $PHPShopGUI->setHelp('��� ��� ������ �������. ��� ������� ���� � ��������� ���� �������������� ��������� ���������� ��������.');



    // �������� 1
    $PHPShopGUI->setEditor('ace', true);

    $oFCKeditor = new Editor('content_new');
    $oFCKeditor->Height = '520';
    $oFCKeditor->Value = $data['content'];
    $Tab2 = $oFCKeditor->AddGUI();



    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1), array("����������", $Tab2), array("���", $Tab3));

    // ����� ������ ��������� � ����� � �����
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "delID", "�������", "right", 70, "", "but", "actionDelete.modules.edit") .
            $PHPShopGUI->setInput("submit", "editID", "���������", "right", 70, "", "but", "actionUpdate.modules.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "���������", "right", 80, "", "but", "actionSave.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// ������� ��������
function actionDelete() {
    global $PHPShopOrm;
    $action = $PHPShopOrm->delete(array('id' => '=' . $_POST['rowID']));
    return array("success" =>  $action);
}

// ��������� �������
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setAction($_GET['id'], 'actionStart', 'none');
?>