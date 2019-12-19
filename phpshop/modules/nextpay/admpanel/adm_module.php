<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.nextpay.nextpay_system"));

// ���������� ������ ������
function actionBaseUpdate()
{
    global $PHPShopModules, $PHPShopOrm;
    $PHPShopOrm->clean();
    $option = $PHPShopOrm->select();
    $new_version = $PHPShopModules->getUpdate($option['version']);
    $PHPShopOrm->clean();
    $action = $PHPShopOrm->update(array('version_new' => $new_version));
    return $action;
}

// ������� ����������
function actionUpdate()
{
    global $PHPShopOrm;

    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&id=nextpay');

    return $action;
}

function actionStart()
{
    global $PHPShopGUI, $PHPShopOrm;

    $PHPShopGUI->addJSFiles("../../phpshop/modules/nextpay/admpanel/ajax/ajax.js");

    // �������
    $data = $PHPShopOrm->select();

    $Tab1 = $PHPShopGUI->setField('ID �������� ��� ��������� ������', $PHPShopGUI->setInputText(false, 'merchant_key2_new',
        $data['merchant_key2'], 250));
    $Tab1 .= $PHPShopGUI->setField('��������� ����', $PHPShopGUI->setInputText(false, 'merchant_skey_new',
        $data['merchant_skey'], 250));

    $Tab1 .= $PHPShopGUI->setField('��������� ���������� �� ������ �������', $PHPShopGUI->setTextarea('link_top_text_new', $data['link_top_text']));
    $Tab1 .= $PHPShopGUI->setField('���������� �� ������ �������', $PHPShopGUI->setTextarea('link_text_new', $data['link_text']));

    // ����� ����� ��������� ������ �� ������
    $Tab2 = $PHPShopGUI->setField('����� ������', $PHPShopGUI->setInputText(false, 'f_oder', '', 250));
    $Tab2 .= $PHPShopGUI->setField('����� � ������', $PHPShopGUI->setInputText(false, 'f_sum', '', 250));
    $Tab2 .= $PHPShopGUI->setField('E-mail �������', $PHPShopGUI->setInputText(false, 'f_email', '', 250));
    $Tab2 .= '<div style="margin: 10px; color: #EEA236;" id="text_link"></div>';
    $Tab2 .= $PHPShopGUI->setInput(
        'button',
        'button_link',
        '������������� ������ �� �����',
        $float = null,
        $size = false,
        $onclick = 'ajax_link()',
        $class = "btn  btn-default btn-sm navbar-btn",
        $action = false,
        $caption = false,
        $description = false,
        $title = false
    );
    $Tab2 .= "&nbsp;&nbsp;&nbsp;";
    $Tab2 .= $PHPShopGUI->setInput(
        'button',
        'button_link',
        '��������� �� e-mail',
        $float = null,
        $size = false,
        $onclick = 'ajax_link_email()',
        $class = "btn  btn-default btn-sm navbar-btn",
        $action = false,
        $caption = false,
        $description = false,
        $title = false
    );

    $info = '
<p>
�������� ������ � ������������ ������, � ����������� �������� ��� ��� ��������.
��� ������ ��� ���������� �������� � ������������ � 54-�� ��� �������� ����������� ������������� ��������� �����. ��������� � ������ ������� � ������ <a href="https://www.nextpay.ru/faq54.php?p=phpshop" target="_blank">������� ��� ������������ 54-��</a>. </p>

<h4>��������� ������</h4>
       <ol>
       <li>������������������ � <a href="http://nextpay.ru/?p=phpshop" target="_blank">NextPay</a>. ��� ������ ��� ���������� ��������� ������������ ��� ������ ������ �� ����������� ����� �������� � ���� "�������� �����" ����� "����������� ����/�� (��� ���������� ��������)" � ��������� ��������� ����� �����������.
        <li>��� ��������� � �������� ������ �� ������ �� ��. ����� ���������� ���������� ������� ������� � �������� �������� � ������� nextpay.ru � ������� <kbd>��������</kbd> - <kbd>������� �������</kbd></li>
<li>� ���������� �������� � ���� "URL ������" ������� <code>http://' . $_SERVER['SERVER_NAME'] . '/nextpaysuccess/</code></li>
<li>ID �������� ������� � ���������� ������ � ���� ID �������� ��� ��������� ������</li>
        </ol>
        
';

    $Tab3 = $PHPShopGUI->setInfo($info);

    // ����� �����������
    $Tab4 = $PHPShopGUI->setPay();

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1, true), array("��������� ������ �� ������", $Tab2, true), array("����������", $Tab3), array("� ������", $Tab4));

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
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
?>