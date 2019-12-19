<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.print.print_system"));


// ������� ����������
function actionUpdate() {
     header('Location: ?path=modules&install=check');
}

// ��������� ������� ��������
function actionStart() {
    global $PHPShopGUI;

    // ���������� �������� 1
    $Info='
<p>
������ ������� �������� ����� �������� �� ������ /print/������.html
� ������ �������� ����� �������� ������ �� �������� ����� <a href="/print/������.html">�������� �����</a>
</p>
<p>
��� ��������������� ������������ ������ �������� php ��� � ������ <mark>page/page_page_list.tpl</mark>
</p>
<pre>
@php
if(class_exists("PHPShopPrintForma")){
$PHPShopPrintForma=new PHPShopPrintForma();
$PHPShopPrintForma->forma();
}
php@
</pre>
';
    $Tab1=$PHPShopGUI->setInfo($Info);

    // ���������� �������� 2
    $Tab2=$PHPShopGUI->setPay();

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("���������",$Tab1),array("� ������",$Tab2));

    // ����� ������ ��������� � ����� � �����
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", 1) .
            $PHPShopGUI->setInput("submit", "saveID", "���������", "right", 80, "", "but", "actionUpdate.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}


// ��������� �������
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');

?>