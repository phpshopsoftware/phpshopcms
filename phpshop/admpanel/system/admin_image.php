<?php

$TitlePage = __("��������� �����������");
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['system']);

// ����� ������ ����������
function GetFonts($font) {
    global $PHPShopGUI;


    $dir = "../lib/font/";
    if (is_dir($dir)) {
        if (@$dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {

                if (preg_match("/([a-zA-Z0-9_]{1,30}).ttf$/", $file, $match)) {

                    $file = str_replace(array('.ttf'), '', $file);

                    if ($font == $file)
                        $sel = "selected";
                    else
                        $sel = "";

                    if ($file != "." and $file != ".." and !strpos($file, '.'))
                        $value[] = array($file, $file, $sel);
                }
            }
            closedir($dh);
        }
    }

    return $PHPShopGUI->setSelect('option[watermark_text_font]', $value);
}

// ��������� ���
function actionStart() {
    global $PHPShopGUI, $PHPShopModules, $TitlePage, $PHPShopOrm;

    // �������
    $data = $PHPShopOrm->select();
    $option = unserialize($data['admoption']);

    // ������ �������� ����
    $PHPShopGUI->field_col = 3;

    // bootstrap-colorpicker
    $PHPShopGUI->addCSSFiles('./css/bootstrap-colorpicker.min.css');
    $PHPShopGUI->addJSFiles('./js/bootstrap-colorpicker.min.js', './js/jquery.waypoints.min.js', './system/gui/system.gui.js');

    $PHPShopGUI->setActionPanel($TitlePage, false, array('���������'));


    $PHPShopGUI->_CODE = '<p></p>' . $PHPShopGUI->setField('����. ������ ���������', $PHPShopGUI->setInputText(false, 'option[img_w]', $option['img_w'], 100, 'px'), 1, '��� �����������') .
            $PHPShopGUI->setField('����. ������ ���������', $PHPShopGUI->setInputText(false, 'option[img_h]', $option['img_h'], 100, 'px'), 1, '��� �����������') .
            $PHPShopGUI->setField('�������� ���������', $PHPShopGUI->setInputText(false, 'option[width_podrobno]', $option['width_podrobno'], 100, '%'), 1, '��� �����������') .
            $PHPShopGUI->setField('�������� �����������', $PHPShopGUI->setCheckbox('option[image_save_source]', 1, '��������� �������� ����������� ��� ����������', $option['image_save_source'])) .
            $PHPShopGUI->setField('������������', $PHPShopGUI->setCheckbox('option[image_adaptive_resize]', 1, '�������������� ����������� ����� ��� ��������� �������', $option['image_adaptive_resize'])) .
            $PHPShopGUI->setField('�������� ��������', $PHPShopGUI->setCheckbox('option[image_save_name]', 1, '��������� �������� �������� �����������', $option['image_save_name'])) .
            $PHPShopGUI->setField(__("����������"), $PHPShopGUI->setInputText($GLOBALS['SysValue']['dir']['dir'] . '/UserFiles/Image/', "option[image_result_path]", $option['image_result_path'], 400), 1, '���� ���������� ����������� �����������') .
            $PHPShopGUI->setField('����. ������ ���������', $PHPShopGUI->setInputText(false, 'option[img_tw]', $option['img_tw'], 100, 'px'), 1, '��� �����������') .
            $PHPShopGUI->setField('����. ������ ���������', $PHPShopGUI->setInputText(false, 'option[img_th]', $option['img_th'], 100, 'px'), 1, '��� �����������') .
            $PHPShopGUI->setField('�������� ���������', $PHPShopGUI->setInputText(false, 'option[width_kratko]', $option['width_kratko'], 100, '%'), 1, '��� �����������');


    if (empty($option['watermark_text_size']))
        $option['watermark_text_size'] = 20;

    if (empty($option['watermark_text_alpha']))
        $option['watermark_text_alpha'] = 80;

    if (empty($option['watermark_text_color']))
        $option['watermark_text_color'] = '#cccccc';

    $PHPShopGUI->_CODE.=$PHPShopGUI->setCollapse('��������� ����������', $PHPShopGUI->setField('������ ���������', $PHPShopGUI->setCheckbox('option[watermark_big_enabled]', 1, '�������� ������� ����', $option['watermark_big_enabled']), 1, '������ �� ����������� ����������� � ��������� �������� ������') .
            $PHPShopGUI->setField('������ ���������', $PHPShopGUI->setCheckbox('option[watermark_source_enabled]', 1, '�������� ������� ����', $option['watermark_source_enabled']), 1, '������ �� ����������� ��������� ����������� � ��������� �������� ������') .
            $PHPShopGUI->setField("��������� �����������", $PHPShopGUI->setIcon($option['watermark_image'], "watermark_image", false, array('load' => false, 'server' => true)), 1, '����������� � ���������� �����') .
            $PHPShopGUI->setField('��������� �����', $PHPShopGUI->setInputText(false, 'option[watermark_text]', $option['watermark_text'], 200), 1, '������������ ������ ���������� �����������') .
            $PHPShopGUI->setField('���� ������', $PHPShopGUI->setInputColor('option[watermark_text_color]', $option['watermark_text_color'])) .
            $PHPShopGUI->setField('������ ������ ������', $PHPShopGUI->setInputText(false, 'option[watermark_text_size]', $option['watermark_text_size'], 100, 'px')) .
            $PHPShopGUI->setField('����� ������', GetFonts($option['watermark_text_font'])) .
            $PHPShopGUI->setField('������ ���������� ������', $PHPShopGUI->setInputText(false, 'option[watermark_right]', intval($option['watermark_right']), 100, 'px')) .
            $PHPShopGUI->setField('������ ���������� �����', $PHPShopGUI->setInputText(false, 'option[watermark_bottom]', intval($option['watermark_bottom']), 100, 'px')) .
            $PHPShopGUI->setField('������������ ������', $PHPShopGUI->setInputText(false, 'option[watermark_text_alpha]', intval($option['watermark_text_alpha']), 100, '%'), 1, '����� ����� [0-127], ������������� 80%')
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

    // ������� ������ ������ ���������� ������ PHPSgop
    unset($option['prevpanel_enabled']);
    unset($option['helper_enabled']);
    unset($option['message_enabled']);
    unset($option['message_time']);
    unset($option['desktop_enabled']);
    unset($option['desktop_time']);
    unset($option['oplata_1']);
    unset($option['oplata_2']);
    unset($option['oplata_3']);
    unset($option['oplata_4']);
    unset($option['oplata_5']);
    unset($option['oplata_6']);
    unset($option['oplata_7']);
    unset($option['oplata_8']);
    unset($option['seller_enabled']);
    unset($option['update_enabled']);
    unset($option['lang']);
    unset($option['calibrated']);
    unset($option['editor_enabled']);
    unset($option['xmlencode']);

    // ��������� PNG
    $_POST['option']['watermark_image'] = $_POST['watermark_image'];

    // ������������� ������ ��������
    $PHPShopOrm->updateZeroVars('option.image_save_source', 'option.image_adaptive_resize', 'option.image_save_name', 'option.watermark_big_enabled', 'option.watermark_source_enabled');

    if (is_array($_POST['option']))
        foreach ($_POST['option'] as $key => $val)
            $option[$key] = $val;

    // �������� ���� ���������� �����������
    if (stristr($option['image_result_path'], '..') or !is_dir($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['SysValue']['dir']['dir'] . '/UserFiles/Image/' . $option['image_result_path']))
        $option['image_result_path'] = null;

    $_POST['admoption_new'] = serialize($option);

    // �������� ������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['rowID']));


    return array("success" => $action);
}

// ��������� �������
$PHPShopGUI->getAction();
?>