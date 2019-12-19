<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.debug.debug_system"));

// ������� ����������
function actionUpdate() {
    global $PHPShopOrm;

    if (empty($_POST['enabled_new']))
        $_POST['enabled_new'] = 0;

    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&install=check');
    return $action;
}

// ��������� ������� ��������
function actionStart() {
    global $PHPShopGUI, $PHPShopOrm,$select_name;

    $PHPShopGUI->action_button['������'] = array(
        'name' => '������ �������',
        'action' => '../../dev/',
        'class' => 'btn btn-default btn-sm navbar-btn btn-action-panel-blank',
        'type' => 'button',
        'icon' => 'glyphicon glyphicon-blackboard'
    );

    $PHPShopGUI->setActionPanel(__("��������� ������") . ' <span id="module-name">' . ucfirst($_GET['id'] . '</span>'), $select_name, array('��������� � �������', '������'));

    // �������
    $data = $PHPShopOrm->select();

    // ���������� �������� 1
    $Tab1 = $PHPShopGUI->setField('�����������', $PHPShopGUI->setCheckbox('enabled_new', 1, '����� �������������� ��� ������� � ������ /dev/', $data['enabled']));
    $Tab1.=$PHPShopGUI->setField('�������� ����', $PHPShopGUI->setInputText(null, 'text_new', $data['text'], 200));

    // ���������� �������� 2
    $Tab2 = $PHPShopGUI->setPay();

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1), array("� ������", $Tab2));

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
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>