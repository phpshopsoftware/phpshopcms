<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.sape.sape_system"));

// ������� ����������
function actionUpdate() {
    global $PHPShopOrm;

    if (empty($_POST['enabled_new']))
        $_POST['enabled_new'] = 0;

    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&install=check');
    return $action;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm, $TitlePage, $select_name;

// �������
    $data = $PHPShopOrm->select();

    $PHPShopGUI->action_button['�������'] = array(
        'name' => '������� ����� Sape',
        'action' => '../modules/sape/code/',
        'class' => 'btn  btn-default btn-sm navbar-btn btn-action-panel-blank',
        'type' => 'button',
        'icon' => 'glyphicon glyphicon-export'
    );

    $PHPShopGUI->setActionPanel($TitlePage, $select_name, array('�������', '��������� � �������'));

    $Select[] = array("�����", 0, $data['flag']);
    $Select[] = array("������", 1, $data['flag']);

// ������� ������� ��� �����
    $Tab1 = $PHPShopGUI->setField("Sape ID", $PHPShopGUI->setInput("text", "sape_user_new", $data['sape_user']));
    $Tab1.=$PHPShopGUI->setField("���������:", $PHPShopGUI->setInput("text", "title_new", $data['title']));
    $Tab1.= $PHPShopGUI->setField("������������:", $PHPShopGUI->setSelect("flag_new", $Select, 100));
    $Tab1.= $PHPShopGUI->setField("���������� ������:", $PHPShopGUI->setInput("text", "num_new", $data['num'], false, 100));
    $Tab1.= $PHPShopGUI->setField("������:", $PHPShopGUI->setCheckbox("enabled_new", 1, "����� ����� �� �����", $data['enabled']));


// ���������� �������� 2
    $Info = '��� ������ ������ ��������� ��������� � �������� ���������� ������ ����� 4cb48833f491686a2500f80310e072da.
����� ������������ � ���� ���������� SAPE USER ����� � ���������� ��� ����� �� ������ CHMOD 777.
����� �������� �� <a href="../modules/sape/code/" taget="_blank">������</a>.
<p>    
��� ��������� ����� "����� ����� �� �����" Sape ������ ����� ������������� ��������� � ����� ��� ������ ��������� ����  � ����� ������.
</p>  <p>  
��� ������������� ��������� ����� ������ ������ ����� ����� ������� "����� ����� �� �����" � � �������� ���������� @sape@
� ������ ����� �������� <code>main/index.tpl � main/shop.tpl</code>.</p>  
<p>  
��� ���������� � ��������� ������ ����� �����-������ ������� ��������� ���������� �������� ����� ��������� �������, ������� ����� �������� ����, ��������� ����� "����� ����� �� �����" � �������� ���:</p>  

<ul>
<li>������� 1: <pre>
@php echo $GLOBALS["SysValue"]["other"]["sape"]; php@</pre></li>

<li>������� 2:<pre>
@php
if (defined("_SAPE_USER")) {
$PHPShopSapeElement = new PHPShopSapeElement();
$PHPShopSapeElement->links(4);
} else echo "<b>����� ������ �� ��������!</b><br>������ Sape �� ����������!";
// ��� 4 - ���-�� ������ ��� ������
php@
</pre>
</li>
</ul>
';
   $Tab2 = $PHPShopGUI->setInfo($Info) ;

// ����� �����������
    $Tab3 = $PHPShopGUI->setPay();

// ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1, 270), array("��������", $Tab2, 270), array("� ������", $Tab3, 270));

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