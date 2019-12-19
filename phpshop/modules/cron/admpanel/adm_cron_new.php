<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.cron.cron_job"));

// ������� ����������
function actionInsert() {
    global $PHPShopOrm;
    $action = $PHPShopOrm->insert($_POST);
    header('Location: ?path=' . $_GET['path']);
    return $action;
}

// ��������� ������� ��������
function actionStart() {
    global $PHPShopGUI;

    $work[] = array('�������', '');
    $work[] = array('����� ��', 'phpshop/modules/cron/sample/dump.php');
    $work[] = array('����� �����', 'phpshop/modules/cron/sample/currency.php');
    $work[] = array('������ � ������ �������', 'phpshop/modules/cron/sample/product.php');

    $Tab1 = $PHPShopGUI->setField("�������� ������:", $PHPShopGUI->setInput("text.requared", "name_new", '����� ������'));
    $Tab1.=$PHPShopGUI->setField("����������� ����:" , $PHPShopGUI->setInputArg(array('type'=>"text.requared", 'name'=>"path_new", 'size'=>'60%','float'=>'left','placeholder'=>'phpshop/modules/cron/sample/testcron.php')) . $PHPShopGUI->setSelect('work', $work, 200, 'left', false, false,false,false,false,false,'selectpicker', '$(\'input[name=path_new]\').val(this.value);'));
    $Tab1.=$PHPShopGUI->setField("������",$PHPShopGUI->setCheckbox("enabled_new", 1, "��������", 1));
    $Tab1.=$PHPShopGUI->setField("���-�� �������� � ����",$PHPShopGUI->setSelect('execute_day_num_new', $PHPShopGUI->setSelectValue(false),70));



    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1, 270));

    // ����� ������ ��������� � ����� � �����
    $ContentFooter=$PHPShopGUI->setInput("submit","saveID","���������","right",false,false,false,"actionInsert.modules.create");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// ��������� �������
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>