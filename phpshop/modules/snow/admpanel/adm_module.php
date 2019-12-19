<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.snow.snow_system"));

// ������� ����������
function actionUpdate() {
    global $PHPShopOrm;

    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&install=check');
    return $action;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;
    
       // bootstrap-colorpicker
    $PHPShopGUI->addCSSFiles('./css/bootstrap-colorpicker.min.css');
    $PHPShopGUI->addJSFiles('./js/bootstrap-colorpicker.min.js');

    // �������
    $data = $PHPShopOrm->select();


    $e_value[] = array('JQuery Snow 2.0', 1, $data['flag']);
    $e_value[] = array('Snow 1.0', 2, $data['flag']);

    $Tab1=$PHPShopGUI->setField('��� �����������', $PHPShopGUI->setSelect('flag_new', $e_value, 200) . $PHPShopGUI->setHelp('JQuery Snow ������� ����������� �������� ���������� <a href="http://jquery.com/" target="_blank">JQuery</a>. �������� ��� ����� �������� Bootstrap, White_brick � ��������.'));
    $Tab1.=$PHPShopGUI->setField('���� ����',$PHPShopGUI->setInputColor('color_new',$data['color']));

    // ����� �����������
    $Tab3 = $PHPShopGUI->setPay();

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1), array("� ������", $Tab3,));

    // ����� ������ ��������� � ����� � �����
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id']) .
            $PHPShopGUI->setInput("submit", "saveID", "���������", "right", 80, "", "but", "actionUpdate.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// ��������� �������
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
?>