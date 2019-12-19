<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.envybox.envybox_system"));

// ������� ����������
function actionUpdate() {
    global $PHPShopOrm,$PHPShopModules;
    
    // ��������� �������
    $PHPShopModules->updateOption($_GET['id'], $_POST['servers']);

    $action = $PHPShopOrm->update($_POST);

    header('Location: ?path=modules&id='.$_GET['id']);
    return $action;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    $PHPShopGUI->title = "��������� ������ Envybox";

    // �������
    $data = $PHPShopOrm->select();

    $Tab1 = $PHPShopGUI->setField('��� ����� ��� ����������', $PHPShopGUI->setInputText(false, 'widget_id_new', $data['widget_id'], 300,false, false, false,'679f0a2e11f4ab299aa741fb8d211539'));

    $Info = '<h4>��� ������� ������� ������ �������� ����������:</h4>
        <ol>
        <li>����������������� �� ����� <a href="http://envbx.ru/url/ab802d/" target="_blank">Envbx.ru</a></li>
	<li>������� ����� ��� � ���� <kbd>�����</kbd> &rarr; <kbd>�������� ����</kbd>.</li>
	<li>������� ����� <kbd>�������� ���</kbd> � ����������� ���� <kbd>��� ����� ��� ����������</kbd> � ���������� ���� �������� ������.</li>
		</ol>';
    $Tab2 = $PHPShopGUI->setInfo($Info, '200px', '100%');

    // ����� �����������
    $Tab3 = $PHPShopGUI->setPay();

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1,true), array("����������", $Tab2), array("� ������", $Tab3));

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