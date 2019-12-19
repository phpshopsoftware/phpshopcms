<?php

$TitlePage = __('�������������� ������ #' . intval($_GET['id']));

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.button.button_forms"));

// ������� ����������
function actionUpdate() {
    global $PHPShopOrm;

    if (empty($_POST['enabled_new']))
        $_POST['enabled_new'] = 0;

    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['rowID']));
    return array('success'=>$action);
}

// ��������� ������� ��������
function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    // �������
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_GET['id'])));

    $PHPShopGUI->field_col = 1;
    $Tab1 = $PHPShopGUI->setField('��������', $PHPShopGUI->setInputText(false, 'name_new', $data['name']));

    $Tab1.= $PHPShopGUI->setField('���������', $PHPShopGUI->setInputText('�', 'num_new', $data['num'], '100') .
            $PHPShopGUI->setCheckbox('enabled_new', 1, '���.', $data['enabled']));
       
    // �������� 
    $PHPShopGUI->setEditor('ace', true);
    $oFCKeditor = new Editor('content_new');
    $oFCKeditor->Height = '320';
    $oFCKeditor->Value = $data['content'];

    $Tab1.=$PHPShopGUI->setField('HTML ���', $oFCKeditor->AddGUI());

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1, 350));

    // ����� ������ ��������� � ����� � �����
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "delID", "�������", "right", 70, "", "but", "actionDelete.modules.edit") .
            $PHPShopGUI->setInput("submit", "editID", "���������", "right", 70, "", "but", "actionUpdate.modules.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "���������", "right", 80, "", "but", "actionSave.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
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