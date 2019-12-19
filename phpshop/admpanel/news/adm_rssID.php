<?php

$TitlePage = __('�������������� RSS #' . $_GET['id']);
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['rssgraber']);

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm, $PHPShopModules;

    // �������
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_GET['id'])));

    $PHPShopGUI->field_col = 2;
    
        // datetimepicker
    $PHPShopGUI->addJSFiles('./js/bootstrap-datetimepicker.min.js', './news/gui/news.gui.js');
    $PHPShopGUI->addCSSFiles('./css/bootstrap-datetimepicker.min.css');

    $PHPShopGUI->setActionPanel(__("�������������� RSS"), array('�������'), array('���������', '��������� � �������'));

    $Tab1 = $PHPShopGUI->setField("URL:", $PHPShopGUI->setInputText(null, "link_new", $data['link'])) .
            $PHPShopGUI->setField("���� ������:", $PHPShopGUI->setInputDate("start_date_new", PHPShopDate::get($data['start_date']))) .
            $PHPShopGUI->setField("���� ����������:", $PHPShopGUI->setInputDate("end_date_new", PHPShopDate::get($data['end_date']))) .
            $PHPShopGUI->setField("�������� �������:", $PHPShopGUI->setInputText(null, "day_num_new", $data['day_num'], 100, '� ����')) .
            $PHPShopGUI->setField("�������� � ������:", $PHPShopGUI->setInputText(null, "news_num_new", $data['news_num'], 100, '�� ���')) .
            $PHPShopGUI->setField("������", $PHPShopGUI->setRadio("enabled_new", 1, "���.", $data['enabled']) . $PHPShopGUI->setRadio("enabled_new", 0, "����.", $data['enabled']) . '&nbsp;&nbsp;');


    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1));

    // ������ ������ �� ��������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

    // ����� ������ ��������� � ����� � �����
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "delID", "�������", "right", 70, "", "but", "actionDelete.news.edit") .
            $PHPShopGUI->setInput("submit", "editID", "���������", "right", 70, "", "but", "actionUpdate.news.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "���������", "right", 80, "", "but", "actionSave.news.edit");

    // �����
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

/**
 * ����� ����������
 */
function actionSave() {

    // ���������� ������
    actionUpdate();

    header('Location: ?path=' . $_GET['path']);
}

// ������� ����������
function actionUpdate() {
    global $PHPShopOrm, $PHPShopModules;

    if (!empty($_POST['start_date_new']))
        $_POST['start_date_new'] = PHPShopDate::GetUnixTime($_POST['start_date_new']);
    else
        $_POST['start_date_new'] = time();

    if (!empty($_POST['end_date_new']))
        $_POST['end_date_new'] = PHPShopDate::GetUnixTime($_POST['end_date_new']);
    else
        $_POST['end_date_new'] = time();
    
        // ������������� ������ ��������
    $PHPShopOrm->updateZeroVars('enabled_new');

    // �������� ������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);
    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['rowID']));
    
    return array("success" =>  $action);
}

// ������� ��������
function actionDelete() {
    global $PHPShopOrm, $PHPShopModules;

    // �������� ������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);


    $action = $PHPShopOrm->delete(array('id' => '=' . $_POST['rowID']));
    return array("success" =>  $action);
}

// ��������� �������
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setAction($_GET['id'], 'actionStart', 'none');
?>