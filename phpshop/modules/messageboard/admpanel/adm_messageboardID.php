<?php

$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.messageboard.messageboard_log"));

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    $PHPShopGUI->addJSFiles('./js/bootstrap-datetimepicker.min.js', './news/gui/news.gui.js');
    $PHPShopGUI->addCSSFiles('./css/bootstrap-datetimepicker.min.css');

    // �������
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_GET['id'])));


    $Tab1 = $PHPShopGUI->setField("����", $PHPShopGUI->setInputDate("date_new", PHPShopDate::dataV($data['date'], false)));
    $Tab1.=$PHPShopGUI->setField("������", $PHPShopGUI->setCheckbox('enabled_new', '1', '����� �� �����', $data['enabled']));

    $Tab1.=$PHPShopGUI->setField("������������", $PHPShopGUI->setInput("text.requared", "name_new", $data['name']));
    $Tab1.=$PHPShopGUI->setField("E-mail", $PHPShopGUI->setInput("text", "mail_new", $data['mail']));
    $Tab1.=$PHPShopGUI->setField("�������", $PHPShopGUI->setInput("text", "tel_new", $data['tel']));

    $Tab1.=$PHPShopGUI->setField("����:", $PHPShopGUI->setTextarea("title_new", $data['title']));
    $Tab1.=$PHPShopGUI->setField("����������:", $PHPShopGUI->setTextarea("content_new", $data['content'], "", '100%', 200));

// ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1, 350));

    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "delID", "�������", "right", 70, "", "but", "actionDelete.modules.edit") .
            $PHPShopGUI->setInput("submit", "editID", "���������", "right", 70, "", "but", "actionUpdate.modules.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "���������", "right", 80, "", "but", "actionSave.modules.edit");

// �����
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// ������� ����������
function actionUpdate() {
    global $PHPShopOrm;

    $_POST['date_new'] = PHPShopDate::GetUnixTime($_POST['date_new']);
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