<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.cron.cron_job"));

// ������� ����������
function actionUpdate() {
    global $PHPShopOrm;
    if (empty($_POST['enabled_new']))
        $_POST['enabled_new'] = 0;
    if (!empty($_POST['last_execute_new']))
        $_POST['used_new'] = 0;
    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['rowID']));
    return array('success'=>$action);
}

/**
 * ����� ����������
 */
function actionSave() {
    global $PHPShopGUI;


    // ���������� ������
    actionUpdate();

    header('Location: ?path=' . $_GET['path']);
}


// ������� ��������
function actionDelete() {
    global $PHPShopOrm;
    $action = $PHPShopOrm->delete(array('id' => '=' . $_POST['rowID']));
    return array("success" =>  $action);
}

// ��������� ������� ��������
function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    // �������
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_GET['id'])));


    $work[] = array('�������', '');
    $work[] = array('����� ��', 'phpshop/modules/cron/sample/dump.php');
    $work[] = array('����� �����', 'phpshop/modules/cron/sample/currency.php');
    $work[] = array('������ � ������ �������', 'phpshop/modules/cron/sample/product.php');

    $Tab1 = $PHPShopGUI->setField("�������� ������:", $PHPShopGUI->setInput("text.requared", "name_new", $data['name']));
    $Tab1.=$PHPShopGUI->setField("����������� ����:" , $PHPShopGUI->setInputArg(array('type'=>"text.requared", 'name'=>"path_new", 'size'=>'60%','float'=>'left','placeholder'=>'phpshop/modules/cron/sample/testcron.php')) . $PHPShopGUI->setSelect('work', $work, 200, 'left', false, false,false,false,false,false,'selectpicker', '$(\'input[name=path_new]\').val(this.value);'));
    $Tab1.=$PHPShopGUI->setField("������", $PHPShopGUI->setCheckbox("enabled_new", 1, "��������", 1));
    $Tab1.=$PHPShopGUI->setField("���-�� �������� � ����", $PHPShopGUI->setSelect('execute_day_num_new', $PHPShopGUI->setSelectValue($data['execute_day_num']), 70));


    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1));

    // ����� ������ ��������� � ����� � �����
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "delID", "�������", "right", 70, "", "but", "actionDelete.modules.edit") .
            $PHPShopGUI->setInput("submit", "editID", "���������", "right", 70, "", "but", "actionUpdate.modules.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "���������", "right", 80, "", "but", "actionSave.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// ��������� �������
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setAction($_GET['id'], 'actionStart');
?>