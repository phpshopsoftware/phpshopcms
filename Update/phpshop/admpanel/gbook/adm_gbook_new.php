<?php

$TitlePage = __('�������� ������');
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['gbook']);

function actionStart() {
    global $PHPShopGUI, $PHPShopSystem, $PHPShopModules;

    // �������
    $data['datas'] = PHPShopDate::get();
    $data['tema'] = __('����� �� ') . $data['datas'];
    $data['name'] = __('�������������');

    $PHPShopGUI->setActionPanel(__("�������� ������"), false, array('��������� � �������'));

    // datetimepicker
    $PHPShopGUI->addJSFiles('./js/bootstrap-datetimepicker.min.js', './news/gui/news.gui.js');
    $PHPShopGUI->addCSSFiles('./css/bootstrap-datetimepicker.min.css');


    // �������� 1
    $PHPShopGUI->setEditor($PHPShopSystem->getSerilizeParam("admoption.editor"));
    $oFCKeditor = new Editor('answer_new');
    $oFCKeditor->Height = '320';
    $oFCKeditor->Value = $data['answer'];

    // ���������� �������� 1
    $Tab1 = $PHPShopGUI->setField("����:",$PHPShopGUI->setInputDate("date_new", PHPShopDate::get($data['date'])));

    $Tab1.=$PHPShopGUI->setField("���:", $PHPShopGUI->setInput("text", "name_new", $data['name']));

    $Tab1.=$PHPShopGUI->setField("E-mail:", $PHPShopGUI->setInput("text", "mail_new", $data['mail']));

    $Tab1.=$PHPShopGUI->setField("����:", $PHPShopGUI->setTextarea("title_new", $data['title'])) .
            $PHPShopGUI->setField("�����:", $PHPShopGUI->setTextarea("question_new", $data['question'], "", '100%', '200'));
    $Tab1.=$PHPShopGUI->setField("������", $PHPShopGUI->setRadio("enabled_new", 1, "���.", $data['enabled']) . $PHPShopGUI->setRadio("enabled_new", 0, "����.", $data['enabled']));
    // ���������� �������� 2
    $Tab1.= $PHPShopGUI->setField("�����", $oFCKeditor->AddGUI());

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1));

    // ������ ������ �� ��������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, null);

    // ����� ������ ��������� � ����� � �����
    $ContentFooter = $PHPShopGUI->setInput("submit", "saveID", "��", "right", 70, "", "but", "actionInsert.gbook.create");

    // �����
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// ������� ����������
function actionInsert() {
    global $PHPShopOrm, $PHPShopModules;

    $_POST['datas_new'] = time();

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