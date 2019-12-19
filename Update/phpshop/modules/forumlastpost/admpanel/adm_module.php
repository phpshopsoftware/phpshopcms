<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.forumlastpost.ipboard_system"));

// ������� ����������
function actionUpdate() {
    global $PHPShopOrm;


    if (empty($_POST['enabled_new']))
        $_POST['enabled_new'] = 0;
    if (empty($_POST['connect_new']))
        $_POST['connect_new'] = 0;

    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&install=check');
    return $action;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    $data = $PHPShopOrm->select();

    if ($data['flag'] == 1)
        $s2 = "selected";
    else
        $s1 = "selected";


    $Select[] = array("�����", 0, $s1);
    $Select[] = array("������", 1, $s2);

    if ($data['connect'] == 1)
        $c2 = "selected";
    else
        $c1 = "selected";

    $Select_connect[] = array("Socket", 0, $c1);
    $Select_connect[] = array("IFRAME", 1, $c2);

    $Tab1= $PHPShopGUI->setField('URL ������', $PHPShopGUI->setInputText(false,"path_new", $data['path'],false,'/lastpost.php'));
        $Tab1.= $PHPShopGUI->setField("����� �����������:", $PHPShopGUI->setSelect("connect_new", $Select_connect, 150, 1));
    $Tab1.=$PHPShopGUI->setField("������:",$PHPShopGUI->setInput("text", "width_new", $data['width'],false,100).$PHPShopGUI->setHelp('��� ������ IFRAME'));
    $Tab1.=$PHPShopGUI->setField("������: ", $PHPShopGUI->setInput("text", "height_new", $data['height'],false,100).$PHPShopGUI->setHelp('��� ������ IFRAME'));
    $Tab1.=$PHPShopGUI->setField("���������: ",$PHPShopGUI->setInput("text", "title_new", $data['title']));
    $Tab1.= $PHPShopGUI->setField("������������:", $PHPShopGUI->setSelect("flag_new", $Select, 200, 1));
    $Tab1.= $PHPShopGUI->setField("��������� �� �������:", $PHPShopGUI->setInput("text", "num_new", $data['num'], $float = "left", $size = 70));
    $Tab1. $PHPShopGUI->setField("������",$PHPShopGUI->setCheckbox("enabled_new", 1, "����� ����� �� �����", $data['enabled']));


    $Info = '��� ������ ������ ��������� ��������� � �������� ���������� ������ ���� lastpost.php � ������ ����������.<br>
    
���� �������� �� ������: <a target="_blank" href="http://' . $_SERVER['SERVER_NAME'] . '/phpshop/modules/forumlastpost/code/">http://' . $_SERVER['SERVER_NAME'] . '/phpshop/modules/forumlastpost/code/</a>
    <p>
��� ��������� ����� "<b>����� ����� �� �����</b>" ���������� � ��������� ��������� � ������ ����� ������������� ��������� � ����� ��� ������
��������� ���� ������������� � ����� ������.</p>

��� ������������� ��������� ����� ������ ���������, ����� ����� ������� "<b>����� ����� �� �����</b>" � � �������� ���������� <kbd>@forumlastpost@</kbd>
� ������ ����� �������� <mark>main/index.tpl</mark> � <mark>main/shop.tpl</mark>.
';
    $Tab2 = $PHPShopGUI->setInfo($Info);
    $Tab3 = $PHPShopGUI->setPay();

    $PHPShopGUI->setTab(array("��������", $Tab1), array("����������", $Tab2), array("� ������", $Tab3));
    
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", 1) .
            $PHPShopGUI->setInput("submit", "saveID", "���������", "right", 80, "", "but", "actionUpdate.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// ��������� �������
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>