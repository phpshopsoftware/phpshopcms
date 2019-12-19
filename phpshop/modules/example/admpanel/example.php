<?php

// ��������� ���������� �� ������
function getExampleInfo() {
    $PHPShopOrm = new PHPShopOrm('phpshop_modules_example_system');
    $data = $PHPShopOrm->select();
    return $data['example'];
}

// ���������� ���� ������ ����������
function addExampleInfo() {
    global $PHPShopGUI;
    $Tab = $PHPShopGUI->setInfo(getExampleInfo());
    $PHPShopGUI->addTab(array("Example", $Tab));
}

// ��������� �������� � ������� actionStart
$addHandler = array(
    'actionStart' => 'addExampleInfo',
    'actionDelete' => false,
    'actionUpdate' => false
);
?>