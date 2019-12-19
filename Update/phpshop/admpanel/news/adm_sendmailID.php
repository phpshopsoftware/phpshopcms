<?php

$TitlePage = __('�������������� �������� #' . $_GET['id']);
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['newsletter']);

function actionStart() {
    global $PHPShopGUI, $PHPShopSystem, $PHPShopOrm, $PHPShopModules, $result_message;

    // ����� ����
    $PHPShopGUI->addJSFiles('./js/bootstrap-datetimepicker.min.js');
    $PHPShopGUI->addCSSFiles('./css/bootstrap-datetimepicker.min.css');

    // �������
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_GET['id'])));
    $PHPShopGUI->field_col = 2;


    // ��� ������
    if (!is_array($data)) {
        header('Location: ?path=' . $_GET['path']);
    }

    $PHPShopGUI->action_button['��������� � ���������'] = array(
        'name' => '��������� � ���������',
        'action' => 'saveID',
        'class' => 'btn  btn-default btn-sm navbar-btn hidden-xs',
        'type' => 'submit',
        'icon' => 'glyphicon glyphicon-ok'
    );


    // ��� ������
    if (strlen($data['name']) > 50)
        $title_name = substr($data['name'], 0, 70) . '...';
    else
        $title_name = $data['name'];

    $PHPShopGUI->setActionPanel(__("��������: " . $title_name), array('�������'), array('���������', '��������� � ���������'));

    // �����
    if (!empty($result_message))
        $Tab1 = $PHPShopGUI->setField('�����', $result_message);

    // �������� 1
    $PHPShopGUI->setEditor($PHPShopSystem->getSerilizeParam("admoption.editor"));
    $oFCKeditor = new Editor('content_new');
    $oFCKeditor->Height = '300';
    $oFCKeditor->Value = $data['content'];

    // ���������� �������� 1
    $Tab1.= $PHPShopGUI->setField("����:", $PHPShopGUI->setInput("text.requared", "name_new", $data['name']));

    $Tab1.=$PHPShopGUI->setField("����� ������:", $oFCKeditor->AddGUI() . $PHPShopGUI->setHelp('����������: <code>@url@</code> - ����� �����, <code>@user@</code> - ��� ����������, <code>@email@</code> - email ����������, <code>@name@</code> - �������� ��������, <code>@tel@</code> - ������� ��������'));

    // �������
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['news']);
    $data_page = $PHPShopOrm->select(array('*'), false, array('order' => 'id desc'), array('limit' => 10));

    $value = array();
    $value[] = array(__('�� ������������'), 0, false);
    if (is_array($data_page))
        foreach ($data_page as $val) {
            $value[] = array($val['title'] . ' &rarr;  ' . $val['date'], $val['id'], false);
        }

    $Tab1.=$PHPShopGUI->setField('���������� �� �������', $PHPShopGUI->setSelect('template', $value, '100%', false, false, false, false, false, false));


    $Tab1.=$PHPShopGUI->setField('����� �����', $PHPShopGUI->setInputText(null, 'send_limit', '0,1000', 150), 1, '������ c 1 �� 1000');


    $Tab1.=$PHPShopGUI->setField("�������� ���������", $PHPShopGUI->setCheckbox('test', 1, '��������� ������ �������� ��������� �� ' . $PHPShopSystem->getEmail(), 1));

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

    //header('Location: ?path=' . $_GET['path']);
}

// ������� ����������
function actionUpdate() {
    global $PHPShopOrm, $PHPShopModules, $PHPShopSystem, $PHPShopGUI, $result_message;

    $_POST['date_new'] = time();

    PHPShopObj::loadClass("parser");
    PHPShopObj::loadClass("mail");

    PHPShopParser::set('url', $_SERVER['SERVER_NAME']);
    PHPShopParser::set('name', $PHPShopSystem->getValue('name'));
    PHPShopParser::set('tel', $PHPShopSystem->getValue('tel'));
    PHPShopParser::set('title', $_POST['name_new']);
    PHPShopParser::set('logo', $PHPShopSystem->getLogo());
    $from = $PHPShopSystem->getEmail();


    // �������� �������
    if (!empty($_POST['template'])) {

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['news']);
        $data = $PHPShopOrm->select(array('*'), array('id' => "=" . intval($_POST['template'])), false, array('limit' => 1));
        if (is_array($data)) {
            $_POST['name_new'] = $data['title'];
            $_POST['content_new'] = $data['content'];
        }
    }

    $n = $error = 0;

    // ����
    if (!empty($_POST['test'])) {

        PHPShopParser::set('user', $_SESSION['logPHPSHOP']);
        PHPShopParser::set('email', $from);
        //PHPShopParser::set('content', @preg_replace("/@([a-zA-Z0-9_]+)@/e", '$GLOBALS["SysValue"]["other"]["\1"]', $_POST['content_new']));
        PHPShopParser::set('content', preg_replace_callback("/@([a-zA-Z0-9_]+)@/", 'PHPShopParser::SysValueReturn', $_POST['content_new']));

        $PHPShopMail = new PHPShopMail($from, $from, $_POST['name_new'], '', true, true);
        $content = PHPShopParser::file('tpl/sendmail.mail.tpl', true);

        if (!empty($content)) {
            if ($PHPShopMail->sendMailNow($content))
                $n++;
            else
                $error++;
        }
    } else {

        // �������� �������������
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['send_mail']);
        $data = $PHPShopOrm->select(array('*'), false, array('order' => 'id desc'), array('limit' => $_POST['send_limit']));

        if (is_array($data))
            foreach ($data as $row) {

                PHPShopParser::set('user', __('������������'));
                PHPShopParser::set('email', $row['mail']);
                //PHPShopParser::set('content', @preg_replace("/@([a-zA-Z0-9_]+)@/e", '$GLOBALS["SysValue"]["other"]["\1"]', $_POST['content_new']));
                PHPShopParser::set('content', preg_replace_callback("/@([a-zA-Z0-9_]+)@/", 'PHPShopParser::SysValueReturn', $_POST['content_new']));

                $PHPShopMail = new PHPShopMail($row['mail'], $from, $_POST['name_new'], '', true, true);
                $content = PHPShopParser::file('tpl/sendmail.mail.tpl', true);

                if (!empty($content)) {
                    if ($PHPShopMail->sendMailNow($content))
                        $n++;
                    else
                        $error++;
                }
            }
    }

    $result_message = $PHPShopGUI->setAlert('������� ��������� �� <strong>' . $n . '</strong> ������� � ������������ ' . $_POST['send_limit'] . ' �������. ������ <strong>' . $error . '</strong>.');

    // �������� ������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['newsletter']);
    $action=$PHPShopOrm->update($_POST, array('id' => '=' . $_POST['rowID']));

    return array("success" => $action);
}

// ������� ��������
function actionDelete() {
    global $PHPShopOrm, $PHPShopModules;

    // �������� ������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $action = $PHPShopOrm->delete(array('id' => '=' . $_POST['rowID']));
    return array("success" => $action);
}

// ��������� �������
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setAction($_GET['id'], 'actionStart', 'none');
?>