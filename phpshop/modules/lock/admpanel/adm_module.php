<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.lock.lock_system"));

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
    
    // �������
    $data = $PHPShopOrm->select();


    $e_value[] = array('����', 1, $data['flag']);
    $e_value[] = array('���', 2, $data['flag']);

    $Tab1=$PHPShopGUI->setField('����������� �� �����', $PHPShopGUI->setSelect('flag_new', $e_value, 200));
    $Tab1.=$PHPShopGUI->setField('����������',$PHPShopGUI->setInput('text.required', "login_new", $data['login'],false,200));
    $Tab1.=$PHPShopGUI->setField('������',$PHPShopGUI->setInput("password.required", "password_new", $data['password'],false,200));

    // ����� �����������
    $Tab3 = $PHPShopGUI->setPay();

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1,true), array("� ������", $Tab3,));

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