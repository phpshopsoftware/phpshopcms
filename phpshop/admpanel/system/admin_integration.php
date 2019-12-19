<?php

$TitlePage = __("��������� ���������� � ���������");
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['system']);

// ��������� ���
function actionStart() {
    global $PHPShopGUI, $PHPShopModules, $TitlePage, $PHPShopOrm, $PHPShopBase;

// �������
    $data = $PHPShopOrm->select();
    $option = unserialize($data['admoption']);

// ������ �������� ����
    $PHPShopGUI->field_col = 3;


    $PHPShopGUI->setActionPanel($TitlePage, false, array('���������'));

    // ����-�����
    if ($PHPShopBase->getParam('template_theme.demo') == 'true') {
        $option['metrica_token'] = '';
    }

    // ������.�������
    $PHPShopGUI->_CODE.=$PHPShopGUI->setCollapse('���������� ��������� ������.�������', $PHPShopGUI->setField('�����', $PHPShopGUI->setInputText(false, 'option[metrica_token]', $option['metrica_token'], 370, '<a target="_blank" href="https://oauth.yandex.ru/authorize?response_type=token&client_id=78246cbd13f74fbd9cb2b48d8bff2559">��������</a>')) .
            $PHPShopGUI->setField('ID �����', $PHPShopGUI->setInputText(null, 'option[metrica_id]', $option['metrica_id'], 300, false, false, false, 'XXXXXXXX') .
                    $PHPShopGUI->setHelp('������ �������� � ������� <a href="?path=metrica">���������� ���������</a>')) .
            $PHPShopGUI->setField("��� ��������", $PHPShopGUI->setCheckbox('option[metrica_enabled]', 1, '�������� ���� ���������� � ���������� ��� ��������', $option['metrica_enabled'])) .
            $PHPShopGUI->setField("������", $PHPShopGUI->setCheckbox('option[metrica_widget]', 1, '�������� ������ ���������� � ������ ������������', $option['metrica_widget']))
            , 'in', false
    );

    // Google Analitiks
    $PHPShopGUI->_CODE.=$PHPShopGUI->setCollapse('���������� ��������� Google',
            $PHPShopGUI->setField('������������� ������������', $PHPShopGUI->setInputText('UA-', 'option[google_id]', $option['google_id'], 300, false, false, false, 'XXXXX-Y').
                    $PHPShopGUI->setHelp('������ �������� � ������� <a href="https://analytics.google.com/analytics/web/" target="_blank">Google ���������</a>')) .
            $PHPShopGUI->setField("��� ��������", $PHPShopGUI->setCheckbox('option[google_enabled]', 1, '�������� ���� ���������� � ���������� ��� ��������', $option['google_enabled'])) 
            , 'in', true
    );

    $PHPShopGUI->_CODE.=$PHPShopGUI->setCollapse('������������ Google reCAPTCHA', $PHPShopGUI->setField("reCAPTCHA", $PHPShopGUI->setCheckbox('option[recaptcha_enabled]', 1, '�������� ����� ��������� �������� �� �����', $option['recaptcha_enabled']), 1, '�������������� ������ ����� �������') .
            $PHPShopGUI->setField("��������� ����", $PHPShopGUI->setInputText(null, "option[recaptcha_pkey]", $option['recaptcha_pkey'], 300)) .
            $PHPShopGUI->setField("��������� ����", $PHPShopGUI->setInputText(null, "option[recaptcha_skey]", $option['recaptcha_skey'], 300) . $PHPShopGUI->setHelp('������������ ����� ��� ������ �������� ����� <a href="https://www.google.com/recaptcha" target="_blank">Google.com</a>'))
    );

    $PHPShopGUI->_CODE.=$PHPShopGUI->setCollapse('��������� DaData.ru', $PHPShopGUI->setField("���������", $PHPShopGUI->setCheckbox('option[dadata_enabled]', 1, '�������� ��������� DaData.ru', $option['dadata_enabled']), 1, '�������������� ������ ����� �������') .
            $PHPShopGUI->setField("��������� ����", $PHPShopGUI->setInputText(null, "option[dadata_token]", $option['dadata_token'], 300) . $PHPShopGUI->setHelp('���������� � �������, �����������, ��������� ������ <a href="https://dadata.ru" target="_blank">DaData.ru</a>'))
    );

     $PHPShopGUI->_CODE.=$PHPShopGUI->setCollapse('��������� �����', $PHPShopGUI->setField("RSS", $PHPShopGUI->setCheckbox('option[rss_graber_enabled]', 1, '��������� ������� �� ������� RSS �������', $option['rss_graber_enabled']). $PHPShopGUI->setHelp('��������� ������ ����������� �  ������� <a href="?path=news.rss">RSS ������</a>'))
    );

    // ������ ������ �� ��������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

    // ����� ������ ��������� � ����� � �����
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("submit", "editID", "���������", "right", 70, "", "but", "actionUpdate.system.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "���������", "right", 80, "", "but", "actionSave.system.edit");

    $PHPShopGUI->setFooter($ContentFooter);

    $sidebarleft[] = array('title' => '���������', 'content' => $PHPShopGUI->loadLib('tab_menu', false, './system/'));
    $PHPShopGUI->setSidebarLeft($sidebarleft, 2);

    // �����
    $PHPShopGUI->Compile(2);
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

    // �������
    $data = $PHPShopOrm->select();
    $option = unserialize($data['admoption']);

    // ������������� ������ ��������
    $PHPShopOrm->updateZeroVars('option.recaptcha_enabled', 'option.dadata_enabled', 'option.sms_enabled', 'option.sms_status_order_enabled', 'option.notice_enabled', 'option.metrica_enabled', 'option.metrica_widget', 'option.metrica_ecommerce','option.google_enabled', 'option.google_analitics','option.rss_graber_enabled');

    if (is_array($_POST['option']))
        foreach ($_POST['option'] as $key => $val)
            $option[$key] = $val;


    $_POST['admoption_new'] = serialize($option);

    // �������� ������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['rowID']));


    return array("success" => $action);
}

// ��������� �������
$PHPShopGUI->getAction();
?>