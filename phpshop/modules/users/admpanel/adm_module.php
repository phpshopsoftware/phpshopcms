<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.users.users_system"));


// ������� ����������
function actionUpdate() {
    global $PHPShopOrm;

    if(empty($_POST['enabled_new'])) $_POST['enabled_new']=0;
    if(empty($_POST['captcha_new'])) $_POST['captcha_new']=0;
    if(empty($_POST['stat_flag_new'])) $_POST['stat_flag_new']=0;

    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&install=check');
}

// ��������� ������� ��������
function actionStart() {
    global $PHPShopGUI,$PHPShopOrm;

    // �������
    $data = $PHPShopOrm->select();

    $Select[]=array("�����",0,$data['flag']);
    $Select[]=array("������",1,$data['flag']);

    // ������������ ����������
    $Select2[]=array("���",0,$data['stat_flag']);
    $Select2[]=array("�����",2,$data['stat_flag']);
    $Select2[]=array("������",1,$data['stat_flag']);

    // ��������� �� e-mail
    $Select3[]=array("��������� ������������ �� e-mail",1,$data['mail_check']);
    $Select3[]=array("������ ��������� ������������",2,$data['mail_check']);

    $Tab1=$PHPShopGUI->setField("������������ ����� �����������:",
             $PHPShopGUI->setSelect("flag_new",$Select,250,1).
            $PHPShopGUI->setLine().
            $PHPShopGUI->setCheckbox("enabled_new",1,"����� ����� �� �����",$data['enabled']));

    $Tab1.=$PHPShopGUI->setField("������������ ����� ����������:",$PHPShopGUI->setSelect("stat_flag_new",$Select2,250));
    $Tab1.=$PHPShopGUI->setLine();
    $Tab1.=$PHPShopGUI->setField("Captcha:",$PHPShopGUI->setCheckbox("captcha_new",1,'������ �� �����',$data['captcha']));
    $Tab1.=$PHPShopGUI->setField("���������:",$PHPShopGUI->setSelect("mail_check_new",$Select3,250,1));

    $Info='��� ���������� � ������� �������� � �������� �������� ����������� ����������� �������� ������������� ���������� $_SESSION[UserName]
     ��� �����������:
     <pre>$PHPShopUsersElement = new PHPShopUsersElement();
     if($PHPShopUsersElement->is_autorization()) ����������� ��������
     </pre>
     <p>
     ��� ���������� ����� � ����� ����������� �������������� ���� <mark>/phpshop/modules/users/templates/users_forma_register.tpl</mark>, �������� � ����
     ��������� ���� � ��������� dop_, ������:</p>
          <pre>&lt;input  type="text" name="dop_�������" size="25"&gt;</pre>
     ��� ������� � ����� ����� � ������ ������� ������������ �����������:
          <pre>$PHPShopUsersElement = new PHPShopUsersElement();
$PHPShopUsersElement->getParam("�������");</pre>
     ��� ������������� ���������� ����� ����������� ��������� ����� ������ ����� �� ����� � ����������� ���������� <kbd>@autorizationForma@</kbd> � <kbd>@onlineForma@</kbd> ��� ������� � ���� ������.
';
    $Tab2=$PHPShopGUI->setInfo($Info);


    // ���������� �������� 2
    $Tab3=$PHPShopGUI->setPay();

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������",$Tab1,true),array("��������",$Tab2),array("� ������",$Tab3));

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