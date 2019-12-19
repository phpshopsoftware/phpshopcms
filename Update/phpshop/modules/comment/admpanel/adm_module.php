<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.comment.comment_system"));

// ������� ����������
function actionUpdate() {
    global $PHPShopOrm;

    if(empty($_POST['enabled_new'])) $_POST['enabled_new']=0;
    if(empty($_POST['flag_new'])) $_POST['flag_new']=0;

    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&install=check');
}

// ��������� ������� ��������
function actionStart() {
    global $PHPShopGUI,$PHPShopOrm;

    // �������
    $data = $PHPShopOrm->select();

    $Select[]=array("������",1,$data['flag']);
    $Select[]=array("�����",0,$data['flag']);
    
    $Tab1=$PHPShopGUI->setField("������������ ����� ������������:",$PHPShopGUI->setSelect("flag_new",$Select,100,1));
    $Tab1.=$PHPShopGUI->setField("�����:", $PHPShopGUI->setCheckbox("enabled_new",1,"����� ����� �� �����",$data['enabled']));

     $Info='
     ��� ������������� ���������� ����� ������ ��������� ������������ ��������� ����� ������ ����� �� ����� � ����������� ���������� <kbd>@lastcommentForma@</kbd>
     ��� ������� � ���� ������ � ������������ �����.
';
    $Tab2=$PHPShopGUI->setInfo($Info);

    // ���������� �������� 2
    $Tab3=$PHPShopGUI->setPay();

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������",$Tab1,true),array("��������",$Tab2),array("� ������",$Tab3),array("�����������", null,'?path=modules.dir.comment'));

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