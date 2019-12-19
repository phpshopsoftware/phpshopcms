<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.cart.cart_system"));

// ������� ����������
function actionUpdate() {
    global $PHPShopOrm;

// �������� ���� �������
    $_FILES['file']['ext'] = PHPShopSecurity::getExt($_FILES['file']['name']);
    if ($_FILES['file']['ext'] == "csv") {
        if (move_uploaded_file($_FILES['file']['tmp_name'], "../../UserFiles/Files/" . $_FILES['file']['name']))
            $_POST['filedir_new'] = $_FILES['file']['name'];
    }

// �������� ���� ��������
    $_FILES['catalog']['ext'] = PHPShopSecurity::getExt($_FILES['catalog']['name']);
    if ($_FILES['catalog']['ext'] == "csv") {
        if (move_uploaded_file($_FILES['catalog']['tmp_name'], "../../UserFiles/Files/" . $_FILES['catalog']['name']))
            $_POST['catdir_new'] = $_FILES['catalog']['name'];
    }

    if (empty($_POST['enabled_new']))
        $_POST['enabled_new'] = 0;
    if (empty($_POST['enabled_market_new']))
        $_POST['enabled_market_new'] = 0;
    if (empty($_POST['enabled_search_new']))
        $_POST['enabled_search_new'] = 0;
    if (empty($_POST['enabled_speed_new']))
        $_POST['enabled_speed_new'] = 0;

    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&install=check');
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm, $PHPShopSystem;


    // �������
    $data = $PHPShopOrm->select();


    // ���������� �������� 1
    $Tab1 = $PHPShopGUI->setField("���� ������:", $PHPShopGUI->setInput("file", "file", "") . $PHPShopGUI->setHelp('<a target="_blank" href="../../phpshop/modules/cart/install/price.csv">������ �����</a> ��� ����������. ������� ��������: <a href="/UserFiles/Files/'. $data['filedir'].'" target="_blank">/UserFiles/Price/'. $data['filedir'].'</a>'));
    $Tab1.=$PHPShopGUI->setField("���� ��������:", $PHPShopGUI->setInput("file", "catalog", "") . $PHPShopGUI->setHelp('<a target="_blank" href="../../phpshop/modules/cart/install/catalog.csv">������ �����</a> ��� ����������. ������� ��������: <a href="/UserFiles/Files/'. $data['catdir'].'" target="_blank">/UserFiles/File/'. $data['catdir'].'</a>'));
    $Tab1.=$PHPShopGUI->setField("E-mail:", $PHPShopGUI->setInputText("", "email_new", $data['email'], 200));
    $Tab1.=$PHPShopGUI->setField("������:", $PHPShopGUI->setInputText("", "valuta_new", $data['valuta'], 100));


    $PHPShopGUI->setEditor($PHPShopSystem->getSerilizeParam("admoption.editor"), true);
    $oFCKeditor = new Editor('message_new', true);
    $oFCKeditor->Height = '200';
    $oFCKeditor->ToolbarSet = 'Normal';
    $oFCKeditor->Value = $data['message'];

    $Tab1.=$PHPShopGUI->setField("���������:", $oFCKeditor->AddGUI());



    $Tab1.= $PHPShopGUI->setField("����� ������:", $PHPShopGUI->setCheckbox("enabled_speed_new", 1, "��������� ���� ������� ������ � ������ (���������� �������� ������ ��������� �������) ", $data['enabled_speed']) .
            $PHPShopGUI->setLine() .
            //$PHPShopGUI->setCheckbox("enabled_market_new", 1, "����� ������� � ������� � ������� ������", $data['enabled_market']) .
            //$PHPShopGUI->setLine() .
            $PHPShopGUI->setCheckbox("enabled_new", 1, "����� ������� �� �����", $data['enabled']) .
            $PHPShopGUI->setLine() .
            $PHPShopGUI->setCheckbox("enabled_search_new", 1, "����� ������� ������ ��� ������", $data['enabled_search']));
    $Tab1.= $PHPShopGUI->setField("��������� ������:", $PHPShopGUI->setInputText("", "num_new", $data['num'], 100));

    // ���������� �������� 2
    $Info = '��� ������ ������ ��������� ������� ��������� ���� (*.csv) � �������� ����������, ���������� ������ �� ������.

������ ���������� ����� ���� �������:
<pre>
ID;�������;������������;����;���������
page1;prod1;����;100;1
page2;prod2;��� �����;1000;1
page3;prod3;����������;1500;2
</pre>

<p>
���:<br>
ID  - �� ������ ��� ������ �� ��������<br>
��������� - ID ��������� �� ����� �������� �������
</p>

<p>
������ ���������� ����� �������� �������:</p>
<pre>
ID;������������
1;�������
2;�������
</pre>

<p>
<h4>�������</h4>
<p>
��� ������ ����� ������� �� ����� ���������� ���������� <kbd>@miniCart@</kbd>� �������� <mark>/main/index.tpl</mark> � <mark>/main/shop.tpl</mark> � ������ ��� �����.
���������� @miniCart@ ������������� ������������ � ������ ������ ���������� �����. ���� ��� ����� ���������� �� � ������ �����, �� ������� �������
"����� ������� �� �����" � ������� ���������� @miniCart@ � ������ ������.</p>
<p>
�����-���� ��������: http://' . $_SERVER['SERVER_NAME'] . '/price/<br>
����� ������:  http://' . $_SERVER['SERVER_NAME'] . '/order/
    </p>
<p>
��� ���������� ������ �� �����-���� � ������� ���� �������� ����� �������� � ������� ���� � ������� ../price/price<br>
������ ����� ������ ��������� � ����� phpshop/modules/cart/templates/order_forma.tpl<br>
��� ���������� ����� ����� � ����� ������ ������ �������� ����� ���� � ���� ����.<br>
</p>
<p>
��� ���������� ����� "����� ������� � �������" ���� ��������� ������ ��������� ����� ��������� ��� ������ ������ market �
�������� � �������� ����� ������������ � �������. ��� ������� ����� � ���� ������ � ���� �������, �������� ������� ����� ������, �����
�������� � �������������� ����������� �� ���� � ������� ���������� � �������.
</p>
<p>
��� ������������� ��������� ����� ���������� � ������� ����� ����� ������� "����� ������� � �������" � ��� ������ ������� � ���� ��������
�������� ������ 
<pre>
@php $Product = new ProductDisp(3); php@</pre>
��� 3 - ��� ID ������ � ����� ����.
';
    $Tab3 = $PHPShopGUI->setInfo($Info);

    $Tab5 = $PHPShopGUI->setPay();

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1, true), array("��������", $Tab3), array("� ������", $Tab5));

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