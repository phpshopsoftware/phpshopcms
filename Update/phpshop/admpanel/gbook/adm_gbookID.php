<?php

$TitlePage = __('�������������� ������ #' . $_GET['id']);
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['gbook']);

function actionStart() {
    global $PHPShopGUI, $PHPShopSystem, $PHPShopOrm, $PHPShopModules;

    // �������
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_GET['id'])));

        // datetimepicker
    $PHPShopGUI->addJSFiles('./js/bootstrap-datetimepicker.min.js', './news/gui/news.gui.js');
    $PHPShopGUI->addCSSFiles('./css/bootstrap-datetimepicker.min.css');
    
        $PHPShopGUI->action_select['������������'] = array(
        'name' => '������������',
        'url' => '../../gbook/ID_' . $data['id'] . '.html',
        'action' => 'front',
        'target' => '_blank'
    );

    $PHPShopGUI->setActionPanel(__("�������������� ������ �� " . $data['name']), array('������������','|','�������'), array('���������', '��������� � �������'));

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
    $Tab1.= $PHPShopGUI->setField("�����",$oFCKeditor->AddGUI());

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1));

    // ������ ������ �� ��������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

    // ����� ������ ��������� � ����� � �����
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "delID", "�������", "right", 70, "", "but", "actionDelete.gbook.edit") .
            $PHPShopGUI->setInput("submit", "editID", "���������", "right", 70, "", "but", "actionUpdate.gbook.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "���������", "right", 80, "", "but", "actionSave.gbook.edit");

    // �����
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// ������� �������� �����
function sendMail($name, $mail) {
    global $PHPShopSystem, $PHPShopBase;

    // ���������� ���������� �������� �����
    PHPShopObj::loadClass("mail");

    $zag = "��� ����� �������� �� ���� " . $PHPShopSystem->getValue('name');
    $message = "��������� " . $name . ",

��� ����� �������� �� ���� �� ������: http://" . $PHPShopBase->getSysValue('dir.dir') . $_SERVER['SERVER_NAME'] . "/gbook/

������� �� ����������� �������.";
    new PHPShopMail($PHPShopSystem->getEmail(), $mail, $zag, $message);
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

    // �������� ������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $_POST['date_new'] = PHPShopDate::GetUnixTime($_POST['date_new']);
    if (empty($_POST['enabled_new']))
        $_POST['enabled_new'] = 0;
    else if (!empty($_POST['mail_new']))
        sendMail($_POST['name_new'], $_POST['mail_new']);

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