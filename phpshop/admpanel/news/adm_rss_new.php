<?php

$TitlePage = __('�������� RSS ������');
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['rssgraber']);

function actionStart() {
    global $PHPShopGUI, $PHPShopModules;

    // �������
    $data['start_date'] = time();
    $data['end_date'] = time() + 10000000;
    $data['enabled'] = 1;
    $data['day_num'] = 1;
    $data['news_num'] = 3;

    $PHPShopGUI->field_col = 2;

    // datetimepicker
    $PHPShopGUI->addJSFiles('./js/bootstrap-datetimepicker.min.js', './news/gui/news.gui.js');
    $PHPShopGUI->addCSSFiles('./css/bootstrap-datetimepicker.min.css');

    $PHPShopGUI->setActionPanel(__("�������� RSS"), false, array('��������� � �������'));

    $Tab1 = $PHPShopGUI->setField("URL:", $PHPShopGUI->setInputArg(array('type' => 'text.required', 'name' => "link_new", 'value' => $data['link'], 'placeholder' => 'http://www.phpshop.ru/rss/'))) .
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
    $ContentFooter = $PHPShopGUI->setInput("submit", "saveID", "��", "right", 70, "", "but", "actionInsert.news.create");

    // �����
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// ������� ����������
function actionInsert() {
    global $PHPShopOrm, $PHPShopModules;

    if (!empty($_POST['start_date_new']))
        $_POST['start_date_new'] = PHPShopDate::GetUnixTime($_POST['start_date_new']);
    else
        $_POST['start_date_new'] = time();

    if (!empty($_POST['end_date_new']))
        $_POST['end_date_new'] = PHPShopDate::GetUnixTime($_POST['end_date_new']);
    else
        $_POST['end_date_new'] = time();

    // �������� ������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $action = $PHPShopOrm->insert($_POST);
    header('Location: ?path=' . $_GET['path']);
    return $action;
}

// ��������� �������
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>
