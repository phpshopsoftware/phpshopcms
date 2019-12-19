<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.guard.guard_system"));

function setSelectValue($n) {
    $i = 1;
    while ($i <= 10) {
        if ($n == $i)
            $s = "selected";
        else
            $s = "";
        $select[] = array($i, $i, $s);
        $i++;
    }
    return $select;
}

function actionStart() {
    global $PHPShopGUI,  $PHPShopOrm;

    // �������
    $data = $PHPShopOrm->select();

    // ���������� �������� 1
    $Tab1 = $PHPShopGUI->setField('�������������� ��������', $PHPShopGUI->setCheckbox("enabled_new", 1, "��������", $data['enabled']));
    $Tab1.=$PHPShopGUI->setField('���������� �������� � ����', $PHPShopGUI->setSelect('chek_day_num_new', setSelectValue($data['chek_day_num']), 50));
    $Tab1.=$PHPShopGUI->setField('�����', $PHPShopGUI->setCheckbox("mode_new", 1, "����������� ����� �������� ������", $data['mode']).$PHPShopGUI->setHelp('������� ��������� �������� ���������� ������. ������������� ��������� ������ �� VPS-���������.'));
    $Tab1.=$PHPShopGUI->setField('�����������', $PHPShopGUI->setCheckbox("stop_new", 1, "���������� ����� ��� ����������� ������", $data['stop']) .
            $PHPShopGUI->setCheckbox("mail_enabled_new", 1, "����������� �������������� �� E-mail", $data['mail_enabled']));
    $Tab1.=$PHPShopGUI->setField('E-mail ��� �������', $PHPShopGUI->setInputText(null, 'mail_new', $data['mail'], 300));


    // �������� ��� �������
    if ((date('U') - $data['last_chek']) < (86400 * 3))
        $flag_chek = '<p class="text-success">'.PHPShopDate::dataV($data['last_chek']).'</p>';
    else
        $flag_chek = '<p class="text-danger">�� ���������</p>';

    if ((date('U') - $data['last_update']) < (86400 * 5))
        $flag_update = '<p class="text-success">'.PHPShopDate::dataV($data['last_update']).'</p>';
    else
        $flag_update = '<p class="text-danger">�� ���������</p>';

    if ((date('U') - $data['last_crc']) < (86400 * 3))
        $flag_crc = '<p class="text-success">'.PHPShopDate::dataV($data['last_crc']).'</p>';
    else
        $flag_crc = '<p class="text-danger">�� ���������</p>';

    $Tab1.=$PHPShopGUI->setField('�������� ������<br>' . $flag_chek , '<a class="btn btn-sm btn-success" style="width:200px" href="../modules/guard/admin.php?do=chek" target="_blank"><span class="glyphicon glyphicon-ok"></span> ��������� �����</a>');

    $Tab1.=$PHPShopGUI->setField('���������� ��������<br>' . $flag_update , '<a class="btn btn-sm btn-success" style="width:200px" href="../modules/guard/admin.php?do=update" target="_blank"><span class="glyphicon glyphicon-refresh"></span> �������� ���������</a>');

    $Tab1.=$PHPShopGUI->setField('�������� ����<br>' . $flag_crc, '<a class="btn btn-sm btn-success" style="width:200px" href="../modules/guard/admin.php?do=create" target="_blank"><span class="glyphicon glyphicon-signal"></span> ����������� ����</a>');

    // ����������
    $Info = ' <h4>������</h4>
���� "�������������� �������� ������" �������� �������������� �������� ������ �� ��������� ����������, ��������������� � ����� "<b>���������� �������� � ����</b>".
    ���� "<b>����������� ����� �������� ������</b>" ���������� ����������� �������� ���� ������, ������� ������� � ����������. ���� ��� ����� �� ��������, �� �������� ���������� ����� ��������, ������� ��������� ������ ����� "����������" ����� � �������. ����������� ����� ������� ������ �������� � ����� ��������� ������ �����. 
<h4>�����������</h4>
    ���� "<b>���������� ����� ��� ����������� ������</b>" ����� ������� �������� ������ �����, ����� ��������� ������������ �� ���������� ������� ����� ��� ���� � ���������� �� �������� ��� � ��������.
    ���� "<b>����������� �������������� �� E-mail</b>" ���������� ������ � ��������, ���������� ������ "�������������� �������� ������". 
<h4>��������</h4>
    �������� ������ - ��������� ����� �� ���������, � ���������� ������ ����������� ��������� �������
    ���������� �������� - ���������� � ���������� �������� � ������� ������������.
    �������� �������� ���� - ����� ������ � �������� ����� �� ����������� ���� ������ 
<h4>�������</h4>
��� ��������� ������� ����� ������������ ������� ������ ������. ��� ������ �������� � UserFiles/Files/ � ����� ��� ����-���������_�����.zip ����� ��� ����������� ����� �� ������� (������������ ��������) ��� ������� ����� ������ ����� ����� ftp ���� �� ���������, ����������� � ��������� ���������� � �������� ����� ������� / 

<h4>�������� ������</h4>
<ol>
<li>���� �� ����������� ���������� ��, �� ��������� ��� ���������� ����� "�������������� �������� ������" ��������� �� �������������� ����������� ���� ������ � ������� ��� �� ����. � ���� ������ ���������� ��������� �������� �� ���������� ��������.<br><br>
<li>���� �� �������� ����������� � ��������� �������, �� � ������ �������������� (����������, ����� ���� "����������� �������������� �� E-mail" ��� �������) ����� ��������� ���� �������� ����� (backup) � ������� ������������ �������. ����� ����� ������������ � ������ ������ � �������� ����� �� ��� ����� ����� ���-��������. </ol>
';
    $Tab2 = $PHPShopGUI->setInfo($Info);

    // ���������� �������� 2
    $Tab3 = $PHPShopGUI->setPay();

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1,true), array("����������", $Tab2), array("� ������", $Tab3));


    // ����� ������ ��������� � ����� � �����
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", 1) .
            $PHPShopGUI->setInput("submit", "saveID", "���������", "right", 80, "", "but", "actionUpdate.modules.edit");

    // �����
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// ������� ����������
function actionUpdate() {
    global $PHPShopOrm;
    $PHPShopOrm->debug = true;
    if (empty($_POST['mode_new']))
        $_POST['mode_new'] = 0;
    if (empty($_POST['enabled_new']))
        $_POST['enabled_new'] = 0;
    if (empty($_POST['stop_new']))
        $_POST['stop_new'] = 0;
    if (empty($_POST['mail_enabled_new']))
        $_POST['mail_enabled_new'] = 0;

    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['rowID']));
    header('Location: ?path=modules&install=check');
    return $action;
}

// ��������� �������
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
?>