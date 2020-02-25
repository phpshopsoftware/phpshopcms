<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.verbox.verbox_system"));

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

    $PHPShopGUI->title = "��������� ������ Verbox";

    // �������
    $data = $PHPShopOrm->select();
    
    // �������� 
    $PHPShopGUI->setEditor('ace', true);
    $oFCKeditor = new Editor('code_new');
    $oFCKeditor->Height = '250';
    $oFCKeditor->Value = $data['code'];

    $Tab1 = $PHPShopGUI->setField('��� ��� �������',  $oFCKeditor->AddGUI());

    $Info = '<h4>��� ������� ������� ������ �������� ����������:</h4>
        <ol>
        <li>����������������� �� ����� <a href="https://admin.verbox.ru/r/phpshop" target="_blank">Verbox.ru</a></li>
	<li>������� ����� ��� � ���� <kbd>��� �����</kbd> &rarr; <kbd>���������� ����</kbd>.</li>
	<li>� ���� <kbd>���������</kbd> ����������� ���� <code>��� ��� ������� �� ����</code> � ���������� ���� �������� ������.</li>
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