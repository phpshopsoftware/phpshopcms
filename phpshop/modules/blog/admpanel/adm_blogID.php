<?php

$TitlePage = __('�������������� ������ #' . intval($_GET['id']));

$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.blog.blog_log"));

function actionStart() {
    global $PHPShopGUI, $PHPShopSystem, $PHPShopOrm;

    // ����� ����
    $PHPShopGUI->addJSFiles('./js/bootstrap-datetimepicker.min.js', './news/gui/news.gui.js');
    $PHPShopGUI->addCSSFiles('./css/bootstrap-datetimepicker.min.css');

    // �������
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . $_GET['id']));

    // �������� 1
    $PHPShopGUI->setEditor($PHPShopSystem->getSerilizeParam("admoption.editor"), true);
    $oFCKeditor = new Editor('description_new', true);
    $oFCKeditor->Height = '320';
    $oFCKeditor->Value = $data['description'];

    // ���������� �������� 1
    $Tab1 = '<hr>' . $PHPShopGUI->setField("����:", $PHPShopGUI->setInputDate("date_new", $data['date'])) .
            $PHPShopGUI->setField("���������:", $PHPShopGUI->setInput("text", "title_new", $data['title']));

    $Tab1.=$PHPShopGUI->setField("�����:", $oFCKeditor->AddGUI());

    // �������� 2
    $oFCKeditor = new Editor('content_new');
    $oFCKeditor->Height = '550';
    $oFCKeditor->ToolbarSet = 'Normal';
    $oFCKeditor->Value = $data['content'];

    // ���������� �������� 2
    $Tab2 = $oFCKeditor->AddGUI();

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1), array("��������", $Tab2));


    // ����� ������ ��������� � ����� � �����
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "delID", "�������", "right", 70, "", "but", "actionDelete.modules.edit") .
            $PHPShopGUI->setInput("submit", "editID", "���������", "right", 70, "", "but", "actionUpdate.modules.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "���������", "right", 80, "", "but", "actionSave.modules.edit");

    // �����
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
    return array("success" => $action);
}

// ������� ����������
function actionUpdate() {
    global $PHPShopOrm;

    if (empty($_POST['enabled_new']))
        $_POST['enabled_new'] = 0;

    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['rowID']));
    return array('success' => $action);
}

// ��������� ������� 
$PHPShopGUI->getAction();


// ����� ����� ��� ������
$PHPShopGUI->setAction($_GET['id'], 'actionStart', 'none');
?>