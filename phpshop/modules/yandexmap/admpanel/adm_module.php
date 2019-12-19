<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.yandexmap.yandexmap_system"));


// ������� ����������
function actionUpdate() {
    global $PHPShopOrm;

    $action = $PHPShopOrm->update($_POST);
    return $action;
}


function actionStart() {
    global $PHPShopGUI,$PHPShopOrm;
    
    // �������
    $data = $PHPShopOrm->select();


    $Tab1=$PHPShopGUI->setTextarea('code_new', $data['code'], null, '98%', 300);
    $Tab3=$PHPShopGUI->setPay();
    $Info='<h4>��� ������� ������.����� �������� ����������</h4>
        <ol>
        <li> <a href="http://api.yandex.ru/maps/form.xml" target="_blank">�������� API ���� ��� ������ �����</a>
        <li> <a href="http://api.yandex.ru/maps/tools/constructor/" target="_blank">�������� ����� �� �����</a>.������� ����� �����.
        ���������� �� ����� ����� � ����� � ��������� ��.
        <li> �������� ��� ��� �������.
        <li> ���������� ��� � �������� � �������� "��� ������" �������� ���� ��������� ������.
        <li> ����� ����� �������� �� ���������� <kbd>@yandexmap@</kbd>. ��� ������� ���������� <kbd>@yandexmap@</kbd> ��������� � �������� ��������, �������� ����� ��������������
        HTML ���� �������� � �������� � ������ ����� <kbd>@yandexmap@</kbd>.

</ol>';
    $Tab2=$PHPShopGUI->setInfo($Info);

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��� ������",$Tab1),array("��������",$Tab2),array("� ������",$Tab3));
    
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