<?php

// ������� ����������
function actionUpdate() {
   header('Location: ?path=modules&install=check');
}

// ��������� ������� ��������
function actionStart() {
    global $PHPShopGUI;


    $Info = '<p>��� ������ ������� � ������� ����������� ���������� <kbd>@sticker_������@</kbd>. 
        ������ ����������� � ����������� ���� �������� �������������� �������. 
        ��� ������� ����������� ������ ���� �� ��������� �����.
        </p> 
         <p>
         ��� ���������� ������� � ������ ������ �������� ��������� ��� � ���������� �������� ��� ���������� �����:
        <p>
        <pre>
@php
$PHPShopStickerElement = new PHPShopStickerElement();
echo $PHPShopStickerElement->forma("������ �������");
php@
        </pre>
         </p>';

    $Tab2 = $PHPShopGUI->setInfo($Info);


    // ���������� �������� 2
    $Tab3 = $PHPShopGUI->setPay();

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("����������", $Tab2), array("� ������", $Tab3),array("����� ��������", null,'?path=modules.dir.sticker'));

    // ����� ������ ��������� � ����� � �����
    $ContentFooter =
            $PHPShopGUI->setInput("submit", "saveID", "���������", "right", 80, "", "but", "actionUpdate.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// ��������� �������
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>